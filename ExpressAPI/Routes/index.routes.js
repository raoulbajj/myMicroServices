const express = require('express');
const router = express.Router();
const indexController = require('../Controllers/index.controller');

router.get('/', function (req, res) {
    res.send('Hello World !')
});

router.post('/', indexController.helloWorld);

module.exports = router;