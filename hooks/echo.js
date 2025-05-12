// import { useEffect, useState } from 'react';
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// if (typeof window !== 'undefined') {
//   window.Pusher = Pusher;
// }

// const useEcho = () => {
//   const [echo, setEcho] = useState(null);

//   useEffect(() => {
//      console.log('useEcho useEffect triggered');
//     if (typeof window === 'undefined') return; // Only run on client

//     const token = sessionStorage.getItem('auth_token'); // Assurez-vous que c'est la même clé

//     const echoInstance = new Echo({
//       broadcaster: 'reverb',
//       key: process.env.NEXT_PUBLIC_REVERB_APP_KEY,
//       wsHost: process.env.NEXT_PUBLIC_REVERB_HOST,
//       wsPort: process.env.NEXT_PUBLIC_REVERB_PORT || 80,
//       wssPort: process.env.NEXT_PUBLIC_REVERB_PORT || 443,
//       forceTLS: (process.env.NEXT_PUBLIC_REVERB_SCHEME ?? 'https') === 'https',
//       enabledTransports: ['ws', 'wss'],
//       authEndpoint: 'http://127.0.0.1:8000/broadcasting/auth', // URL complète
//       auth: {
//         headers: {
//           Authorization: `Bearer ${token}`,
//           Accept: 'application/json',
//         },
//       },
//     });

//     setEcho(echoInstance);

//     return () => {
//       echoInstance.disconnect();
//     };
//   }, []);

//   return echo;
// };

// export default useEcho;

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//       key: process.env.NEXT_PUBLIC_REVERB_APP_KEY,
//       wsHost: process.env.NEXT_PUBLIC_REVERB_HOST,
//       wsPort: process.env.NEXT_PUBLIC_REVERB_PORT || 80,
//       wssPort: process.env.NEXT_PUBLIC_REVERB_PORT || 443,
//       forceTLS: (process.env.NEXT_PUBLIC_REVERB_SCHEME ?? 'https') === 'https',
//       enabledTransports: ['ws', 'wss'],
//     authEndpoint: 'http://127.0.0.1:8000/broadcasting/auth', 
//     auth: {
//         headers: {
//             Authorization: `Bearer ${sessionStorage.getItem('auth_token')}`, 
//         },
//     },
// });

// lib/echo.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

let echoInstance = null;

if (typeof window !== 'undefined') {
  window.Pusher = Pusher;
  echoInstance = new Echo({
    broadcaster: 'reverb',
    key: process.env.NEXT_PUBLIC_REVERB_APP_KEY,
    wsHost: process.env.NEXT_PUBLIC_REVERB_HOST || 'localhost',
    wssPort: process.env.NEXT_PUBLIC_REVERB_PORT || 443,
    wsPort:  process.env.NEXT_PUBLIC_REVERB_PORT || 80,
    forceTLS: (process.env.NEXT_PUBLIC_REVERB_SCHEME ?? 'https') === 'https',
    authEndpoint: 'http://localhost:8000/broadcasting/auth',
    auth: {
      headers: {
        Authorization: `Bearer ${sessionStorage.getItem('auth_token')}`
      }
    }
  });
  window.Echo = echoInstance;
}

export const getEcho = () => echoInstance;
