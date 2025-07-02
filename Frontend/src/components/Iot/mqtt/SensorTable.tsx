import React, { useMemo } from "react";
import { motion } from "framer-motion";
import {
  createColumnHelper,
  flexRender,
  getCoreRowModel,
  getPaginationRowModel,
  useReactTable,
} from "@tanstack/react-table";
import { SensorTableProps } from "@/types/iot/iotmqtt";

const columnHelper = createColumnHelper<SensorTableProps["realTimeData"][0]>();

export const SensorTable: React.FC<SensorTableProps> = ({ realTimeData, selectedDataType }) => {
  const columns = useMemo(
    () => [
      columnHelper.accessor("device_code", {
        header: "Sensor",
        cell: (info) => info.getValue() || "N/A",
      }),
      columnHelper.accessor("fecha_medicion", {
        header: "Fecha",
        cell: (info) =>
          info.getValue()
            ? new Date(info.getValue()!).toLocaleString("es-ES", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
              })
            : "N/A",
      }),
      columnHelper.accessor(row => row[selectedDataType.key], {
        header: selectedDataType.nombre,
        cell: (info) => {
          const value = info.getValue() as number | null;
          return typeof value === "number" ? value.toFixed(selectedDataType.decimals) : "N/A";
        },
      }),
    ],
    [selectedDataType]
  );

  const filteredData = useMemo(
    () =>
      realTimeData.filter(
        (dato) => dato[selectedDataType.key] !== null && dato[selectedDataType.key] !== undefined
      ),
    [realTimeData, selectedDataType]
  );

  const table = useReactTable({
    data: filteredData,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    initialState: { pagination: { pageSize: 10, pageIndex: 0 } },
  });

  return (
    <motion.div
      className="bg-white p-6 rounded-lg shadow-md w-full max-w-4xl mx-auto"
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
    >
      <h2 className="text-lg font-semibold mb-4 text-gray-800">{selectedDataType.nombre}</h2>
      <div className="overflow-x-auto">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            {table.getHeaderGroups().map((headerGroup) => (
              <tr key={headerGroup.id}>
                {headerGroup.headers.map((header) => (
                  <th
                    key={header.id}
                    className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    {flexRender(header.column.columnDef.header, header.getContext())}
                  </th>
                ))}
              </tr>
            ))}
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {table.getRowModel().rows.map((row) => (
              <tr key={row.id}>
                {row.getVisibleCells().map((cell) => (
                  <td key={cell.id} className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {flexRender(cell.column.columnDef.cell, cell.getContext())}
                  </td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      {filteredData.length === 0 && (
        <p className="text-gray-600 text-center mt-4">No hay datos disponibles para {selectedDataType.nombre}.</p>
      )}
      <div className="flex justify-between mt-4">
        <button
          className="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:bg-blue-300"
          onClick={() => table.previousPage()}
          disabled={!table.getCanPreviousPage()}
        >
          Anterior
        </button>
        <span className="text-gray-600">
          Página {table.getState().pagination.pageIndex + 1} de {table.getPageCount()}
        </span>
        <button
          className="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:bg-blue-300"
          onClick={() => table.nextPage()}
          disabled={!table.getCanNextPage()}
        >
          Siguiente
        </button>
      </div>
    </motion.div>
  );
};

export default SensorTable;