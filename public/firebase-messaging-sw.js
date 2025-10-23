importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "xxx",
    authDomain: "xxx.firebaseapp.com",
    projectId: "xxx",
    storageBucket: "xxx.appspot.com",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "xxx",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    // show notification
    const { title, body } = payload.notification;
    self.registration.showNotification(title, { body });
});
