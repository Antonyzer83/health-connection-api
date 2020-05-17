CREATE DATABASE IF NOT EXISTS health_connection;

use health_connection;

CREATE TABLE IF NOT EXISTS users (
    identifiant VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255),
    role ENUM('citoyen', 'medecin', 'admin')
);