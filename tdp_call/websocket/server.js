
// server.js

// Import required modules
const WebSocket = require('ws');                        // For WebSocket library
const cors = require('cors');                           // For CORS middleware
const fs = require('fs');                               // For File system module
const winston = require('winston');                     // For Logging library
const path = require('path');                           // For Path module
const mysql = require('mysql2/promise');                // For MySQL database library
const express = require('express');                     // For Express web framework
const fetch = require('node-fetch');                    // For node fetch module
const clients = new Set();                              // Store connected clients in a Set
const app = express();                                  // Create an Express app
const { Server: WebSocketServer } = require('ws');	// Import WebSocketServer from 'ws'
const { connectToDatabase } = require('./db');          // Import a function to connect to the database
const Logger = require('./log');                        // Import a log function


//define the campaignId
var campaignId;
var userId;

// Create a WebSocket server without an HTTP server
const wss = new WebSocket.Server({ noServer: true });


// WebSocket connection open event
  wss.on('connection', (ws, request) => {

 console.log('Client connected');


    // Add the client to the set of connected clients
    clients.add(ws);


  // Handle incoming messages from obd_call and neron
    ws.on('message', async (message) => {
        Logger.info("campaign_list response from neron");
      console.log(`Performing Actions: ${message}`);

    // Parse the incoming message
    const obj = JSON.parse(message);

// Log the message to the Neron logger
    Logger.info(obj);

// Store the ActionId in the variable campaignId if it exists in the message
if (obj && obj.data && obj.data.ActionID) {
  // If the ActionID exists, store it in campaignId
   campaignId = obj.data.ActionID;
}

    // Store the CDR report into the database if the message represents a CDR event
       if (obj && obj.data && obj.data.Event === "Cdr") {

        // Connect to the database
       const db = await connectToDatabase();

 let userId; // Declare userId here and initialize it as undefined


try {
// Query the database to find the user_id associated with the campaignId
        const [userRows] = await db.execute("SELECT userId FROM calls WHERE campaignId = ?", [campaignId]);

        if (userRows.length > 0) {
          userId = userRows[0].userId;
        } else {
            console.error(`No user found for campaignId ${campaignId}`);
        }

//Insert the data into CDRS table
const sql = `
	INSERT INTO cdrs (campaignId, accountcode, src, dst, clid, channel, calldate, answerdate, hangupdate, billsec, disposition, amaflags, recordurl, direction, entry_date, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Y')
      `;
      const values = [
        campaignId,
        obj.data.accountcode,
        obj.data.src,
        obj.data.dst,
        obj.data.callid,
        obj.data.dsttech,
        obj.data.start,
        obj.data.answer || null,
        obj.data.end,
        obj.data.billsec,
        obj.data.disposition,
        obj.data.amaflags,
        obj.data.recordurl,
        obj.data.direction,
      ];
const [rows, fields] = await db.execute(sql, values);

console.log("CDRS report created successfully");


// Update the used_credits if the disposition is "answered"
if (obj.data.disposition === "ANSWERED") {
        const sqlUpdateCredits = `
            UPDATE user_credits
            SET used_credits = used_credits + 1
            WHERE user_id = ?;
        `;
	const valuesUpdateCredits = [userId];
        const [updateRows, updateFields] = await db.execute(sqlUpdateCredits, valuesUpdateCredits);

}


//update the flag after generating the call
      const sqlUpdate = `
  UPDATE calls
  SET flag = 'c'
  WHERE mobile = ?
`;
const valuesUpdate = [obj.data.dst];
const [updateRows, updateFields] = await db.execute(sqlUpdate, valuesUpdate);

} catch (error) {
  console.error("Error:", error);
} finally {
  db.close();
}
}


// Broadcast the message to all clients except the sender
clients.forEach((client) => {
  if (client === ws && client.readyState === WebSocket.OPEN) {
        Logger.info("campaign_list response to api");
  //send the message to neron
    client.send(JSON.stringify(obj));

    // Log the message to the API logger
       Logger.info(obj);

}
});
});

// WebSocket connection close event
ws.on('close', () => {
  console.log('Client disconnected');

  // Remove the client from the set of connected clients
  clients.delete(ws);
});
});


// Export the WebSocket server for use in other modules
module.exports = wss;




