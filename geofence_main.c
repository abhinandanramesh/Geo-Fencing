
#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <fcntl.h>
#include <sys/signal.h>
#include <errno.h>
#include <termios.h>
#include <sys/ioctl.h>

//define interfacing details for first
#define firstBAUD B9600
#define firstDEVICE "/dev/ttyS2"

#define EILABZ_IOC_MAGIC  'q'
/* Please use a different 8-bit number in your code */

#define BUZZER_ON    _IO(EILABZ_IOC_MAGIC, 0)
#define BUZZER_OFF    _IO(EILABZ_IOC_MAGIC, 1)
#define TRUE 1
#define FALSE 0
int counter =0;

typedef struct{

float longitude;
float latitude;

}Point;

Point Pointer[100],P;
int fd2;
int i;
unsigned char ch1[]={0,0,0,0,0};
 char longitude[100];
 char latitude[100];
char a[100];
unsigned char ch;
int inOrOut=0;

char *substr (const char *inpStr, int startPos, int strLen) {
    /* Cannot do anything with NULL. */
    char *buff;
    if (inpStr == NULL) return NULL;

    /* All negative positions to go from end, and cannot
       start before start of string, force to start. */

    if (startPos < 0)
        startPos = strlen (inpStr) + startPos;
    if (startPos < 0)
        startPos = 0;

    /* Force negative lengths to zero and cannot
       start after end of string, force to end. */

    if (strLen < 0)
        strLen = 0;
    if (startPos >strlen (inpStr))
        startPos = strlen (inpStr);

    /* Adjust length if source string too short. */

    if (strLen > strlen (&inpStr[startPos]))
        strLen = strlen (&inpStr[startPos]);

    /* Get long enough string from heap, return NULL if no go. */

    if ((buff =(char *) malloc (strLen + 1)) == NULL)
        return NULL;

    /* Transfer string section and return it. */

    memcpy (buff, &(inpStr[startPos]), strLen);
    buff[strLen] = '\0';

    return buff;
}

// a Point is defined by its coordinates {int x, y;}
//===================================================================
 

// isLeft(): tests if a point is Left|On|Right of an infinite line.
//    Input:  three points P0, P1, and P2
//    Return: >0 for P2 left of the line through P0 and P1
//            =0 for P2  on the line
//            <0 for P2  right of the line
//    See: Algorithm 1 "Area of Triangles and Polygons"
inline int isLeft(Point P0, Point P1, Point P2 )
{
    return ( (P1.latitude- P0.latitude) * (P2.longitude - P0.longitude)
            - (P2.latitude-  P0.latitude) * (P1.longitude - P0.longitude) );
}
//===================================================================


// cn_PnPoly(): crossing number test for a point in a polygon
//      Input:   P = a point,
//               V[] = vertex points of a polygon V[n+1] with V[n]=V[0]
//      Return:  0 = outside, 1 = inside
// This code is patterned after [Franklin, 2000]
int cn_PnPoly( Point P, Point* V, int n )
{
    int    cn = 0;    // the  crossing number counter
int i, vt;
inOrOut=0;
    // loop through all edges of the polygon
    for (i=0; i<n; i++) {    // edge from V[i]  to V[i+1]
       if (((V[i].longitude <= P.longitude) && (V[i+1].longitude > P.longitude))     // an upward crossing
        || ((V[i].longitude > P.longitude) && (V[i+1].longitude <=  P.longitude))) { // a downward crossing
            // compute  the actual edge-ray intersect x-coordinate
            vt = (int) (P.longitude  - V[i].longitude) / (V[i+1].longitude - V[i].longitude);
            if (P.latitude <  V[i].latitude + vt * (V[i+1].latitude - V[i].latitude)) // P.x < intersect
                 ++cn;   // a valid crossing of y=P.y right of P.x

        }
    }
    printf("%d\n", cn);
    inOrOut=cn&1;
    return (cn&1);    // 0 if even (out), and 1 if  odd (in)

}
//===================================================================


// wn_PnPoly(): winding number test for a point in a polygon
//      Input:   P = a point,
//               V[] = vertex points of a polygon V[n+1] with V[n]=V[0]
//      Return:  wn = the winding number (=0 only when P is outside)
int
wn_PnPoly( Point P, Point* V, int n )
{
    int    wn = 0;    // the  winding number counter
int i;
    // loop through all edges of the polygon
    for (i=0; i<n; i++) {   // edge from V[i] to  V[i+1]
        if (V[i].longitude <= P.longitude) {          // start y <= P.y
            if (V[i+1].longitude  > P.longitude)      // an upward crossing
                 if (isLeft( V[i], V[i+1], P) > 0)  // P left of  edge
                     ++wn;            // have  a valid up intersect
        }
        else {                        // start y > P.y (no test needed)
            if (V[i+1].longitude  <= P.longitude)     // a downward crossing
                 if (isLeft( V[i], V[i+1], P) < 0)  // P right of  edge
                     --wn;            // have  a valid down intersect
        }
    }
        inOrOut=wn;
    return wn;
}
//===================================================================



void initfirstSerial()
{
    
    struct termios newtio2;
        
    /* open the device to be non-blocking (read will return immediatly) */
    fd2 = open(firstDEVICE, O_RDWR | O_NOCTTY | O_NONBLOCK);
    if (fd2 <0) 
    {
        perror(firstDEVICE); 
        exit(-1); 
    }
       
    /* install the signal handler before making the device asynchronous */
    
        
    newtio2.c_cflag = firstBAUD | CS8 | CLOCAL | CREAD;
    newtio2.c_iflag = IGNPAR | ICRNL;
    newtio2.c_oflag = 0;
    newtio2.c_lflag = ICANON;
    newtio2.c_cc[VMIN]=1;
    newtio2.c_cc[VTIME]=0;
    tcflush(fd2, TCIFLUSH);
    tcsetattr(fd2,TCSANOW,&newtio2);     

}

//main
int main(void)
{
    
    int i;
    int fd,fd2;
    float tempLongitude, tempLatitude,Degree,Minutes,Sec;
    int boundaryPointNumber;
    int num=0;
    char geofenceData[1000];
      initfirstSerial();
    //init the first serial
    system(". dialscript3.sh");
    fd2=open("/dev/ttyS2",O_RDWR);
  /*  system(". dialscript5.sh &");
    read(fd2,fenceData,1000);
	Pointer[0].latitude= atof(substr(fenceData,0,8));
    	Pointer[0].longitude= atof(substr(fenceData,0,8));
    	Pointer[1].latitude= atof(substr(fenceData,0,8));
    	Pointer[1].longitude= atof(substr(fenceData,0,8));
    	Pointer[2].latitude= atof(substr(fenceData,0,8));
    	Pointer[2].longitude= atof(substr(fenceData,0,8));
    	Pointer[3].latitude= atof(substr(fenceData,0,8));
    	Pointer[3].longitude= atof(substr(fenceData,0,8));
    	Pointer[4].latitude= atof(substr(fenceData,0,8));
    	Pointer[4].longitude= atof(substr(fenceData,0,8));
    	Pointer[5].latitude= atof(substr(fenceData,0,8));
    	Pointer[5].longitude= atof(substr(fenceData,0,8));
    	*/
  
    fd = open("/dev/buzzer", O_RDWR);
   
    if (fd == -1) {
        perror("Error: cannot open framebuffer device");
        exit(1);
	}
 ioctl(fd,BUZZER_ON);
    sleep(2);
    ioctl(fd,BUZZER_OFF);
    
    //printf("enter the number of boundary points\n");
    //scanf("%d", &boundaryPointNumber);
        
    /*while(num<=boundaryPointNumber)
    {           
        num++;
    printf("enter the point %d longitude value\n", num);

    
    scanf("%f",Point[num].longitude);
    printf("enter the point %d latitude value\n", num);
    scanf("%f",Point[num].longitude);
        }*/
    Pointer[0].longitude=77.549185;
    Pointer[0].latitude=12.990172;
    Pointer[1].longitude=77.552790;
    Pointer[1].latitude=12.98933;
    Pointer[2].longitude=77.5517117;
    Pointer[2].latitude=12.98670;
    Pointer[3].longitude=77.54965;
    Pointer[3].latitude=12.98536;    
    Pointer[4].longitude= 77.548155;
    Pointer[4].latitude= 12.9867022;
  
    
    //printf("your boundary values are ...\n");
    
    while(1)
    {
        for(i=0; i<100; i++)
        {
            a[i]=0x00;
            longitude[i]=0x00;
            latitude[i]=0x00;
        }
        sleep(5);
        system(". gps.sh");
        tcflush(fd2, TCIFLUSH);
        sleep(2);
        ch = 0x00;
        while(ch != '4')
        {
            read(fd2, &ch, 1);
        }
        ch=0x00;
        while(ch != ',')
        {
            read(fd2, &ch, 1);
        }
        read(fd2, latitude, 8);
        printf("Latitude:  ");
        for(i=0; i<8; i++)
        {
            printf("%c", latitude[i]);  
        }
        printf("\n");
        
        ch = 0x00;
        while(ch != ',')
        {
            read(fd2, &ch, 1);
        }
        read(fd2, ch1, 2);
        
        printf("Longitude: ");
        read(fd2, longitude, 8);
        for(i=0; i<8; i++)
        {
            printf("%c", longitude[i]); 
        }
        printf("\n");
       // tempLatitude = (atof(substr(latitude,0,8)))/100; 
	tempLatitude = (atof(substr(latitude,0,8))); 
	Degree=(int)(tempLatitude/100);
        
        Minutes=(float)((int)tempLatitude%100)/60;
	Minutes =Minutes - 0.01;
        
        Sec=(tempLatitude-(int)tempLatitude)/36;
        tempLatitude=Degree+Minutes+Sec;
 
        printf("%f\n", tempLatitude);
        
      // 4330.4850
       //43.513472
    //  system(a);
        sleep(2);
        tempLongitude = (atof(substr(longitude,0,8))); 
        Degree=(int)(tempLongitude/100);
        
        Minutes=(float)((int)tempLongitude%100)/60;
        
        Sec=(tempLongitude-(int)tempLongitude)/36;
        tempLongitude=Degree+Minutes+Sec;
 
        printf("%f\n", tempLongitude);
        //sprintf(a, ". dialscript4.sh longitude %f", tempLongitude);
    P.latitude=tempLatitude;
    P.longitude=tempLongitude;
   // wn_PnPoly( P, Pointer, 5 );
    cn_PnPoly( P, Pointer, 5 );
if((tempLatitude>1) && (tempLongitude>1))
    {
    counter=0;
      ioctl(fd,BUZZER_ON);       ///What is BUZZER_ON?
        sleep(1);
        ioctl(fd,BUZZER_OFF); 
        
        if(inOrOut)     
        {   
        sprintf(a, ". dialscript4.sh lat-long-I/O %f %f inside", tempLatitude, 	tempLongitude);
        system(a);
      /*  ioctl(fd,BUZZER_ON);       ///What is BUZZER_ON?
        sleep(1);
        ioctl(fd,BUZZER_OFF); */
        
        }
        else
        {
        sprintf(a, ". dialscript4.sh lat-long-I/O %f %f outside", tempLatitude, tempLongitude);
        system(a);
        	
   	ioctl(fd,BUZZER_OFF);     /// What is BUZZER_OFF?
        }
      } 
       else
       {
		counter ++;
		if (counter > 4)
		{
		counter=0;
		//execv("gps",0)
		printf("relaunching the app as inputs are zero\n");
		execl ("./gps", "gps", " ", (char *)0);
		
	}
		

        
        //sprintf(a, ". dialscript5.sh %f %f", tempLatitude, tempLongitude);
        //call winding algorithm;       
        //system(a);
        // return is out side then buzz
        
    }
        tcflush(fd2, TCIFLUSH);
        //printf("%s\n", a);
    }
    return 0;

}
