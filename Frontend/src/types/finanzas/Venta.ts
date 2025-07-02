export interface Venta {
  id?: number;
  fecha: string;
  monto_entregado: number;
  cambio: number;
  detalles?: DetalleVenta[];
}

export interface DetalleVenta {
  id?: number;
  venta?: number;
  producto: number; 
  cantidad: number;
  unidad_medidas: number; 
  total: number; 
  precio_unitario?: number; 
}