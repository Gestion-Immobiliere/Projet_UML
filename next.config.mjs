/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    remotePatterns: [
      {
        protocol: 'https',
        hostname: 'a2dimmobilier.com',
        pathname: '/wp-content/uploads/**',
      },
      {
        protocol: 'http',
        hostname: 'localhost',
        port: '8000', // Spécifiez le port si nécessaire
        pathname: '/storage/images/**', // Restreignez aux chemins des images si possible
      },
    ],
  },
};

export default nextConfig;