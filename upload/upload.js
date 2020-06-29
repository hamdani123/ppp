var express	=	require("express");
var multer	=	require('multer');
var cors = require('cors');
var app = express();
var os = require("os");
var hostname = os.hostname();

app.use(cors());

var storage_events	=	multer.diskStorage({
    destination: function (req, file, callback) {
        callback(null, './uploads/events');
    },
    filename: function (req, file, callback) {
        callback(null, file.fieldname + '-' + Date.now()+'.'+file.originalname.split('.').pop());
    }
});

var storage_news =	multer.diskStorage({
    destination: function (req, file, callback) {
        callback(null, './uploads/news');
    },
    filename: function (req, file, callback) {
        callback(null, file.fieldname + '-' + Date.now()+'.'+file.originalname.split('.').pop());
    }
});

var storage_ppp_book	=	multer.diskStorage({
    destination: function (req, file, callback) {
        callback(null, './uploads/ppp_book');
    },
    filename: function (req, file, callback) {
        callback(null, file.fieldname + '-' + Date.now()+'.'+file.originalname.split('.').pop());
    }
});

var upload_events = multer({ storage : storage_events}).single('file_upload');
var upload_news = multer({ storage : storage_news}).single('file_upload');
var upload_ppp_book = multer({ storage : storage_ppp_book}).single('file_upload');
app.use('/events', express.static(__dirname + '/uploads/events'));
app.use('/news', express.static(__dirname + '/uploads/news'));
app.use('/ppp_book', express.static(__dirname + '/uploads/ppp_book'));
app.use('/assets', express.static(__dirname + '/assets'));

app.get('/',function(req,res){
    res.sendFile(__dirname + "/index.html");
});

app.post('/api/events',function(req,res){
    upload_events(req,res,function(err) {
        if(err) {
            return res.end("Error uploading file.");
        }

        res.end('http://' + req.hostname + ':3002/events/' + req.file.filename);
    });
});

app.post('/api/news',function(req,res){
    upload_news(req,res,function(err) {
        if(err) {
            console.log(err);
            return res.end("Error uploading file.");
        }

        res.end('http://' + req.hostname + ':3002/news/' + req.file.filename);
    });
});

app.post('/api/ppp_book',function(req,res){
    upload_ppp_book(req,res,function(err) {
        if(err) {
            console.log(err);
            return res.end("Error uploading file.");
        }

        res.end('http://' + req.hostname + ':3002/ppp_book/' + req.file.filename);
    });
});

app.listen(3002,function(){
    console.log("Working on port 3002");
});