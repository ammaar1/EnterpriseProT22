// function to remove the order from the database
function completeOrder(orderId) {
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyDqvIHMqnePDmY-O_pyjYsfot3YFNtvKf4",
    authDomain: "mysupermarketshopt22.firebaseapp.com",
    databaseURL: "https://mysupermarketshopt22-default-rtdb.firebaseio.com",
    projectId: "mysupermarketshopt22",
    storageBucket: "mysupermarketshopt22.appspot.com",
    messagingSenderId: "94196879488",
    appId: "1:94196879488:web:eb81b6d55548f5f4155665"
  };
  
  // if apps
  if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
  }
  
  // create database
  this.database = firebase.database();
    // get the order
    let orderRef = this.database.ref('Orders/'+orderId);
    // remove it
    orderRef.remove()
    // reload the page with the PHP as well
    window.location.reload(true);
   
}