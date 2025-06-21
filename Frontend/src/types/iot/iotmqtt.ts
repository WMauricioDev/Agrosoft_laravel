export interface SensorData {
  id?: number;
  device_code: string;
  sensor_nombre?: string;
  bancal_nombre?: string;
  temperatura?: number | string;
  humedad_ambiente?: number | string;
  humedad_suelo?: number | string;
  calidad_aire?: number | string;
  luminosidad?: number | string;
  fecha_medicion?: string;
}

export interface DataType {
  label: string;
  key: "temperatura" | "humedad_ambiente" | "humedad_suelo" | "calidad_aire" | "luminosidad";
  icon: React.ReactNode; // Cambiado de Element a React.ReactNode
  tipo_sensor: string;
  decimals: number;
}

export interface ViewMode {
  id: "realtime" | "allData";
  label: string;
}

export interface Sensor {
  id: number;
  nombre: string;
  tipo_sensor: string;
  device_code: string;
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
}