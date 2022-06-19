if (window.Worker) {
    var myWorker = new Worker('worker.js');
    console.log('worker here');

    /* replace first and second with elements */
//    first.onchange = function() {
//        myWorker.postMessage([first.value,second.value]); // Sending message as an array to the worker
//        console.log('Message posted to worker');
//    };
//
//    second.onchange = function() {
//        myWorker.postMessage([first.value,second.value]);
//        console.log('Message posted to worker');
//    };

    myWorker.onmessage = function(e) {
        result.textContent = e.data;
        console.log('Message received from worker');
    };
    
}


//self.addEventListener('message', function(e) {
//    self.postMessage(e.data);
//}, false);
//
//onmessage = function(e) {
//  console.log('Message received from main script');
//  var workerResult = 'Result: ' + (e.data[0] * e.data[1]);
//  console.log('Posting message back to main script');
//  postMessage(workerResult);
//}