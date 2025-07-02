import React from "react";

export interface SensorData {
  id?: number | string;
  device_code: string;
  sensor_nombre?: string;
  bancal_nombre?: string;
  temperatura?: number | null;
  humedad_ambiente?: number | null;
  humedad_suelo?: number | null;
  calidad_aire?: number | null;
  luminosidad?: number | null;
  fecha_medicion?: string;
}

export interface DataType {
  nombre: string;
  key: "temperatura" | "humedad_ambiente" | "humedad_suelo" | "calidad_aire" | "luminosidad";
  icon: React.ReactNode;
  tipo_sensor: string;
  decimals: number;
  medida_minima?: number;
  medida_maxima?: number;
}

export interface ViewMode {
  id: "realtime" | "allData";
  label: string;
}

export interface Sensor {
  id: number;
  nombre: string;
  tipo_sensor: string;
  tipo_sensor_id: number;
  unidad_medida: string;
  descripcion?: string;
  estado: "activo" | "inactivo";
  medida_minima: number;
  medida_maxima: number;
  device_code?: string | null;
  bancal_id?: number | null;
  bancal_nombre?: string | null;
}

export interface SensorChartsProps {
  realTimeData: SensorData[];
  selectedDataType: DataType;
  selectedSensor: number | "todos";
}

export interface SensorStatsProps {
  realTimeData: SensorData[];
  selectedSensor: number | "todos";
}

export interface DataTypeSelectorProps {
  selectedDataType: DataType;
  setSelectedDataType: (type: DataType) => void;
}

export interface ViewModeSelectorProps {
  selectedViewMode: ViewMode;
  setSelectedViewMode: (mode: ViewMode) => void;
}

export interface SensorTableProps {
  realTimeData: SensorData[];
  selectedDataType: DataType;
}