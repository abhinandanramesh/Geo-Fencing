Geo-Fencing
===========

This project was created during a Nokia PESIT hackathon which creates a Geo-Fence. The project was developed along with Harish.

The geo-fence is created using the utilities in the Google MAPS API. The co-ordinates of the geo-fence are defined using a GUI based interface by dragging the corners of the polygon. The javascript files required to interact with the google maps are included in the Google-Maps-API folder. 

For advanced API utilities, refer to https://developers.google.com/maps/documentation/javascript/tutorial

These co-ordinates are fetched and stored in a personal homepage. Use the forms in the server.php folder to host the website.

Finally, we used the Ray-Caster and Winding computational methods to compute whether the current location co-ordinates are within the specified geo-fence.
____________________________________________________________________________________________________________________________

Then the project was extended to built a hand held machine using the LPC1788, an ARM Cortex-M3 based microcontroller and SIM908 GPS module.

The geo-fence main.c file contains the implementation of Ray-Caster and Winding algorithms along with the SIM908 module interface with LPC1788. The C file works perfectly fine with the specified controller and SIM908 module.

However, care must be taken to see that the latitude-longitude format returned by the Google MAPS API must be in sync with the format recorded by the GPS module being used.

Adding geofence_algo.c file, if anyone wants to specifically work on the algorithmic aspect of the geo-fence.
