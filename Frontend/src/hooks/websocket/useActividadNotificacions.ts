import { useEffect, useCallback, useRef } from "react";
import { Notification } from "@/types/notificacion";

export const useActivityNotifications = (
  userId: string | undefined,
  addNotification: (notification: Notification) => void
) => {
  const wsRef = useRef<WebSocket | null>(null);
  const reconnectAttemptsRef = useRef(0);
  const maxReconnectAttempts = 5;
  const reconnectTimeoutRef = useRef<NodeJS.Timeout | null>(null);
  const mountedRef = useRef(true);

  const setupWebSocket = useCallback(() => {
    if (!userId) {
      console.log("No userId provided, skipping WebSocket setup");
      return;
    }

    // Evitar crear un nuevo WebSocket si ya hay uno activo
    if (wsRef.current && wsRef.current.readyState === WebSocket.OPEN) {
      console.log("WebSocket already open, skipping setup");
      return;
    }

    // Limpiar cualquier intento de reconexión previo
    if (reconnectTimeoutRef.current) {
      clearTimeout(reconnectTimeoutRef.current);
      reconnectTimeoutRef.current = null;
    }

    // Cerrar WebSocket existente si está en un estado no cerrado
    if (wsRef.current && wsRef.current.readyState !== WebSocket.CLOSED) {
      wsRef.current.close();
    }

    const socketUrl = `ws://${window.location.hostname}:8000/ws/actividades/notificaciones/${userId}/`;
    const ws = new WebSocket(socketUrl);
    wsRef.current = ws;

    ws.onopen = () => {
      if (!mountedRef.current) return;
      reconnectAttemptsRef.current = 0;
      console.log(`WebSocket connected for userId: ${userId}`);
    };

    ws.onmessage = (event) => {
      if (!mountedRef.current) return;
      try {
        const data = JSON.parse(event.data);
        const newNotification: Notification = {
          id: `notif-${Date.now()}-${data.actividad.id}`,
          type: data.type,
          message: data.message,
          actividad: {
            id: data.actividad.id,
            tipo_actividad_nombre: data.actividad.tipo_actividad_nombre,
            prioridad: data.actividad.prioridad,
            descripcion: data.actividad.descripcion,
          },
          timestamp: new Date().toISOString(),
          source: "activities",
        };
        addNotification(newNotification);
      } catch (error) {
        console.error("Error processing activity notification:", error);
      }
    };

    ws.onerror = (error) => {
      if (!mountedRef.current) return;
      console.error(`WebSocket error for userId: ${userId}`, error);
    };

    ws.onclose = (event) => {
      if (!mountedRef.current) return;
      console.log(`WebSocket closed for userId: ${userId}, code: ${event.code}, reason: ${event.reason}`);
      wsRef.current = null;

      if (reconnectAttemptsRef.current < maxReconnectAttempts) {
        const delay = Math.min(3000 * (reconnectAttemptsRef.current + 1), 15000);
        console.log(`Attempting to reconnect in ${delay}ms (attempt ${reconnectAttemptsRef.current + 1})`);
        reconnectTimeoutRef.current = setTimeout(() => {
          if (mountedRef.current && (!wsRef.current || wsRef.current.readyState !== WebSocket.OPEN)) {
            reconnectAttemptsRef.current++;
            setupWebSocket();
          }
        }, delay);
      } else {
        console.warn("Max reconnect attempts reached");
      }
    };

    return ws;
  }, [userId, addNotification]);

  useEffect(() => {
    mountedRef.current = true;

    if (userId) {
      setupWebSocket();
    } else if (wsRef.current) {
      wsRef.current.close();
      wsRef.current = null;
    }

    return () => {
      mountedRef.current = false;
      if (reconnectTimeoutRef.current) {
        clearTimeout(reconnectTimeoutRef.current);
        reconnectTimeoutRef.current = null;
      }
      if (wsRef.current) {
        wsRef.current.close();
        wsRef.current = null;
      }
    };
  }, [setupWebSocket, userId]);
};