import { useEffect, useCallback, useRef } from "react";
import { Notification } from "@/types/notificacion";

export const usePlagaNotifications = (
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
      console.log("No userId provided, skipping WebSocket setup for plagas");
      return;
    }

    if (wsRef.current && wsRef.current.readyState === WebSocket.OPEN) {
      console.log("WebSocket already open for plagas, skipping setup");
      return;
    }

    if (reconnectTimeoutRef.current) {
      clearTimeout(reconnectTimeoutRef.current);
      reconnectTimeoutRef.current = null;
    }

    if (wsRef.current && wsRef.current.readyState !== WebSocket.CLOSED) {
      wsRef.current.close();
    }

    const socketUrl = `ws://${window.location.hostname}:8000/ws/reportes-plagas/notificaciones/${userId}/`;
    const ws = new WebSocket(socketUrl);
    wsRef.current = ws;

    ws.onopen = () => {
      if (!mountedRef.current) return;
      reconnectAttemptsRef.current = 0;
      console.log(`WebSocket de plagas conectado para userId: ${userId}`);
    };

    ws.onmessage = (event) => {
      if (!mountedRef.current) return;
      try {
        const data = JSON.parse(event.data);
        const newNotification: Notification = {
          id: `plaga-${Date.now()}-${data.reporte.id}`,
          type: data.type === 'cambio_estado_plaga' ? 'info' : 'reporte_plaga',
          message: data.message,
          plaga: {
            id: data.reporte.id,
            plaga_nombre: data.reporte.plaga.nombre,
            bancal_nombre: data.reporte.bancal.nombre,
            estado: data.reporte.estado,
            observaciones: data.reporte.observaciones,
            fecha_reporte: data.reporte.fecha_reporte
          },
          timestamp: new Date().toISOString(),
          source: 'plagas'
        };
        addNotification(newNotification);
      } catch (error) {
        console.error("Error procesando notificación de plaga:", error);
      }
    };

    ws.onerror = (error) => {
      if (!mountedRef.current) return;
      console.error(`WebSocket error para userId: ${userId}`, error);
    };

    ws.onclose = (event) => {
      if (!mountedRef.current) return;
      console.log(`WebSocket de plagas cerrado para userId: ${userId}, code: ${event.code}, reason: ${event.reason}`);
      wsRef.current = null;

      if (reconnectAttemptsRef.current < maxReconnectAttempts) {
        const delay = Math.min(3000 * (reconnectAttemptsRef.current + 1), 15000);
        console.log(`Reintentando conexión WebSocket de plagas en ${delay}ms (intento ${reconnectAttemptsRef.current + 1})`);
        reconnectTimeoutRef.current = setTimeout(() => {
          if (mountedRef.current && (!wsRef.current || wsRef.current.readyState !== WebSocket.OPEN)) {
            reconnectAttemptsRef.current++;
            setupWebSocket();
          }
        }, delay);
      } else {
        console.warn("Se alcanzó el número máximo de reintentos para WebSocket de plagas");
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
