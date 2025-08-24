import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  /* config options here */
  webpack(config) {
    config.module.rules.push({
      test: /\.svg$/,
      use: ["@svgr/webpack"],
    });
    return config;
  },

  images: {
    domains: ["lh3.googleusercontent.com", "github.com", "avatars.githubusercontent.com", "cdn.discordapp.com"],
  }
};

export default nextConfig;
