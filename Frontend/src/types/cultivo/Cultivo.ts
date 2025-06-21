export interface Cultivo {
  id?: number;
  especie_id: number; 
  bancal_id: number; 
  nombre: string;
  unidad_medida_id: number;
  activo: boolean;
  fecha_siembra: string; 
}