import React, { createContext, useContext, useState, ReactNode } from "react";
import { useNavigate } from "react-router-dom";
const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}`;


interface Rol {
  id: number;
  nombre: string;
  created_at: string | null;
  updated_at: string | null;
}

export interface User {
  id: number;
  nombre: string;
  apellido:string;
  email: string;
  numero_documento: number;
  rol: Rol; 
  esAdmin?: boolean; 
}

interface AuthContextType {
  isAuthenticated: boolean;
  user: User | null;
  login: (numero_documento: number, password: string) => Promise<void>;
  logout: () => void;
  updateUser: (updatedUser: User) => void;  
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
const [isAuthenticated, setAuthenticated] = useState<boolean>(!!localStorage.getItem("accesso_token"));

  const [user, setUser] = useState<User | null>(() => {
    const storedUser = localStorage.getItem("user");
    return storedUser ? JSON.parse(storedUser) : null;
  });
  const navigate = useNavigate();

const login = async (numeroDocumento: number, password: string) => {
  try {
    const response = await fetch(`${API_URL}/api/login/`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ numero_documento: numeroDocumento, password }),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || "Error en el login");
    }

    const data = await response.json();
    console.log("🔴Respuesta del login:", data);
    const token = data.access_token;

    if (!token) {
      throw new Error("No se recibió token");
    }

    localStorage.setItem("accesso_token", token);
    localStorage.setItem("refresh_token",token);
    setAuthenticated(true);

    const userResponse = await fetch(`${API_URL}/api/user/me/`, {
      method: "GET",
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    if (!userResponse.ok) {
      throw new Error("No se pudo obtener la información del usuario");
    }

    const userJson = await userResponse.json();
    if (!userJson.data) {
      throw new Error("Respuesta inválida: no se encontró 'data'");
    }

    const userData: User = userJson.data;

    setUser(userData);
    localStorage.setItem("user", JSON.stringify(userData));

    navigate("/");
  } catch (error) {
    console.error("Error en login:", error);
    setAuthenticated(false);
    setUser(null);
    localStorage.removeItem("accesso_token");
    localStorage.removeItem("refresh_token");
    localStorage.removeItem("user");
    throw error;
  }
};

  const logout = () => {
    localStorage.removeItem("accesso_token");
    localStorage.removeItem("refresh_token");
    localStorage.removeItem("user");
    setAuthenticated(false);
    setUser(null);
    navigate("/login");
  };

  const refreshToken = async () => {
  const token = localStorage.getItem("refresh_token");

  if (!token) {
    console.warn("No hay refresh_token");
    logout();
    return;
  }

  try {
    const response = await fetch(`${API_URL}/api/refresh`, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    const data = await response.json();

    if (!response.ok || !data?.data?.access_token) {
      throw new Error("No se pudo refrescar el token");
    }

    const newToken = data.data.access_token;
    localStorage.setItem("accesso_token", newToken);
    console.log("Nuevo access_token guardado correctamente");
    return newToken;
  } catch (error) {
    console.error("Error al refrescar token:", error);
    logout();
  }
};


  const updateUser = (updatedUser: User) => {
    setUser(updatedUser);
    localStorage.setItem("user", JSON.stringify(updatedUser));
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, user, login, logout, updateUser }}>
      {children}
    </AuthContext.Provider>
  );
};



export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth debe usarse dentro de un AuthProvider");
  }
  return context;
};

