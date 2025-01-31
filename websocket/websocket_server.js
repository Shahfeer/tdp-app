// websocket_server.js

// Import required modules
const https = require('https');         // For creating an HTTPS server
const fs = require('fs');               // For file system operations
const winston = require('winston');     // For logging
const express = require('express');     // For creating an Express app
const cors = require('cors');           // For enabling Cross-Origin Resource Sharing (CORS)
const mysql = require('mysql2/promise');// For database connectivity
const wss = require('./server');        // For the WebSocket Server
const WebSocket = require('ws');        // For WebSocket support
const app = express();                  // Create an Express app
const Logger = require('./log');        // Import a log	file function


//define the campaignId
var campaignId;

// Enable CORS middleware
app.use(cors());

// Set JSON and URL-encoded request size limits
app.use(express.json({ limit: '200mb' }));
app.use(
  express.urlencoded({
    extended: true,
    limit: '200mb',
  })
);


// Define API routes
app.post('/obd_call_request', (req, res) => {

Logger.info("campaign_list request from api");

const { queryArray } = req.body;

try {
    // Parse the JSON string into an array
    const jsonArray = JSON.parse(queryArray);

    // Ensure that jsonArray is an array
    if (Array.isArray(jsonArray)) {
      // Iterate through the array
      jsonArray.forEach((query) => {

        //log file
        Logger.info(query);

        console.log(`Performing Actions: ${JSON.stringify(query)}`);


        // Send the query to WebSocket
          wss.clients.forEach((client) => {
          if (client.readyState === WebSocket.OPEN) {
                Logger.info("campaign_list request to neron");
            client.send(JSON.stringify(query), (error) => {
              if (error) {
                console.error('WebSocket send error:', error);
              } else {

                Logger.info(query);

                console.log(`Send to Neron: ${JSON.stringify(query)}`);
}
            });
          }
	});
	
      });
    } else {
      console.error('Invalid jsonArray format. Expected an array.');
    }
  } catch (error) {
    console.error('Error parsing JSON:', error);
    res.status(400).json({ error: 'Invalid JSON format' });
    return;
  }

  res.json({ message: 'Message sent to WebSocket clients' });
});


// Path to SSL certificate and key
const sslCertPath = "/etc/letsencrypt/live/yj360.in/cert.pem";
const sslKeyPath = "/etc/letsencrypt/live/yj360.in/privkey.pem";

// SSL certificate options
const sslOptions = {
  cert: fs.readFileSync(sslCertPath),
  key: fs.readFileSync(sslKeyPath),
};
// Create an HTTPS server
const httpsServer = https.createServer(sslOptions, (req, res) => {
  res.writeHead(200, { 'Content-Type': 'text/plain' });
  res.end('WebSocket server running');
});


// WebSocket server port
const port = 5003;


// Handle WebSocket upgrade requests
httpsServer.on('upgrade', (request, socket, head) => {
  wss.handleUpgrade(request, socket, head, (ws) => {
    wss.emit('connection', ws, request);
  });
});


// Start the API server
const apiPort = 5004;
app.listen(apiPort, () => {
  console.log(`API server is listening on http://localhost:${apiPort}`);
});

// Start the WebSocket server
httpsServer.listen(port, () => {
  console.log(`WebSocket server is listening on wss://103.120.178.190:${port}`);
});


module.exports = Logger; // Export the Logger instance correctly


