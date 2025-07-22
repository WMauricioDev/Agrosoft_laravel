import api from "@/components/utils/axios"; 
const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/`;

export const obtenerNuevoToken = async (refreshToken: string | null) => {
  if (!refreshToken) {
    throw new Error("El refresh token no está disponible.");
  }

  try {
    const response = await api.post("/refresh/", {
      refresh: refreshToken,
    });

    return response.data.access; 
  } catch (error) {
    console.error("Error al refrescar el token", error);
    throw new Error("No se pudo refrescar el token");
  }
};
