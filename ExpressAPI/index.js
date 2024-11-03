// ==== SERVEUR EXPRESS ====
const express = require('express')
const app = express()
app.use(express.json())
app.use(express.urlencoded({ extended: false }))

// ==== DOTENV ====
require('dotenv').config();

// ==== MIDDLEWARES ====
const cors = require('cors')
app.use(cors())

// ==== DB CONNECT ====
const mongoose = require('mongoose')
const mongoURL = process.env.MongoDB_URL
mongoose.connect(mongoURL)
    .then(() => console.log("=========================\nATLAS-DATABASE SUCCESSFULLY CONNECTED !\n=========================\n"))
    .catch(err => console.log(err));

// ==== ROUTES ==== 
app.use('/', require('./Routes/index.routes'));
app.use('/discussion', require('./Routes/discussion.routes'));

// ==== PORT ====
const PORT = process.env.PORT;
app.listen(PORT, () => {
    console.log(`=========================\nServer running on PORT ${PORT}\n=========================\n`);
});