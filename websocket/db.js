
// db.js

// Import the 'mysql2/promise' library
const mysql = require('mysql2/promise');

// Database connection configuration
const dbConfig = {
  host: 'localhost',            // Database host
  user: 'root',                 // Database user
  password: 'YJ.Mysql_P@55word',// Database user password
  database: 'obd_call',         // Database name
};

// Function to connect to the database asynchronously
async function connectToDatabase() {
  try {

// Create a new database connection
    const connection = await mysql.createConnection(dbConfig);
    return connection;
  } catch (error) {
    throw error;
  }
}

// Export the 'connectToDatabase' function to make it accessible from other modules
module.exports = {
  connectToDatabase,
};


