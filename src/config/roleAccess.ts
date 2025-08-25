export const roleAccess = {
  KING: {
    allowed: ["Dashboard", "Orders", "Designers", "Income", "Moderate", "Ekstrakulikuler"],
  },
  ADMIN: {
    allowed: ["Dashboard", "Orders", "Designers", "Profile", "Support"],
  },
  USER: {
    allowed: ["Dashboard", "Designers", "Profile", "Support"],
  },
};