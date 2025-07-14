import React, { useState } from "react";
import DefaultLayout from "@/layouts/default";
import { ReuInput } from "@/components/globales/ReuInput";
import { useRegistrarActividad, useInsumos, useUsuarios } from "@/hooks/cultivo/useActividad";
import { useHerramientas } from "@/hooks/inventario/useHerramientas";
import { useTipoActividad, useRegistrarTipoActividad } from "@/hooks/cultivo/usetipoactividad";
import { useCultivos } from "@/hooks/cultivo/useCultivo";
import { useNavigate } from "react-router-dom";
import Select from 'react-select';
import makeAnimated from 'react-select/animated';
import Formulario from "@/components/globales/Formulario";
import ReuModal from "@/components/globales/ReuModal";
import { Plus } from 'lucide-react';
import { ModalCultivo } from "@/components/cultivo/ModalCultivo";
import { ModalHerramienta } from "@/components/cultivo/ModalHerramienta";
import { ModalInsumo } from "@/components/inventario/ModalInsumo";
import { User } from "@/context/AuthContext";

// Interfaces proporcionadas
interface Actividad {
  id?: number;
  tipo_actividad_id: number;
  descripcion: string;
  fecha_inicio: string;
  fecha_fin: string;
  cultivo_id: number;
  estado: 'PENDIENTE' | 'EN_PROCESO' | 'COMPLETADA' | 'CANCELADA';
  prioridad: 'ALTA' | 'MEDIA' | 'BAJA';
  instrucciones_adicionales?: string;
  usuarios: number[];
  insumos?: { insumo_id: number; cantidad_usada: number }[];
  herramientas?: {
    herramienta_id: number;
    cantidad_entregada: number;
    entregada?: boolean;
    devuelta?: boolean;
    fecha_devolucion?: string | null;
  }[];
  prestamos_insumos?: PrestamoInsumo[];
  prestamos_herramientas?: PrestamoHerramienta[];
  usuarios_data?: User[];
  tipo_actividad_nombre?: string;
  cultivo_nombre?: string;
}

interface PrestamoInsumo {
  id: number;
  actividad_id: number;
  insumo_id: number;
  cantidad_usada: number;
  cantidad_devuelta: number;
  fecha_devolucion?: string;
  unidad_medida_id?: number;
  insumo_nombre?: string;
  unidad_medida?: string;
}

interface PrestamoHerramienta {
  id: number;
  actividad_id: number;
  herramienta_id: number;
  bodega_herramienta_id?: number;
  cantidad_entregada: number;
  cantidad_devuelta: number;
  entregada: boolean;
  devuelta: boolean;
  fecha_devolucion?: string;
  herramienta_nombre?: string;
  bodega_herramienta_cantidad?: number;
}



const animatedComponents = makeAnimated();

const ActividadPage: React.FC = () => {
  const navigate = useNavigate();

  const [actividad, setActividad] = useState<Actividad>({
    descripcion: "",
    fecha_inicio: "",
    fecha_fin: "",
    tipo_actividad_id: 0,
    cultivo_id: 0,
    estado: "PENDIENTE",
    prioridad: "MEDIA",
    instrucciones_adicionales: "",
    usuarios: [],
    insumos: [],
    herramientas: [],
  });

  const [searchUsuario, setSearchUsuario] = useState("");
  const [searchInsumo, setSearchInsumo] = useState("");
  const [searchHerramienta, setSearchHerramienta] = useState("");

  const mutation = useRegistrarActividad();
  const registrarTipoActividad = useRegistrarTipoActividad();
  const { data: tiposActividad } = useTipoActividad();
  const { data: usuarios } = useUsuarios();
  const { data: cultivos } = useCultivos();
  const { data: insumos } = useInsumos();
  const { data: herramientas } = useHerramientas();
  const [openTipoActividadModal, setOpenTipoActividadModal] = useState(false);
  const [openCultivoModal, setOpenCultivoModal] = useState(false);
  const [openHerramientaModal, setOpenHerramientaModal] = useState(false);
  const [openInsumoModal, setOpenInsumoModal] = useState(false);

  const [nuevoTipoActividad, setNuevoTipoActividad] = useState({
    nombre: "",
    descripcion: "",
  });

  const handleSubmitTipoActividad = () => {
    registrarTipoActividad.mutate(nuevoTipoActividad, {
      onSuccess: () => {
        setOpenTipoActividadModal(false);
        setNuevoTipoActividad({ nombre: "", descripcion: "" });
      },
    });
  };

  const usuarioOptions = usuarios?.map((u) => ({ value: u.id, label: u.nombre })) || [];
  const insumoOptions = insumos?.map((i) => ({
    value: i.id,
    label: `${i.nombre} (Disponible: ${i.cantidad})`,
    cantidad: 0,
  })) || [];
  const herramientaOptions = herramientas?.map((h) => ({
    value: h.id,
    label: `${h.nombre}`,
    cantidad_entregada: 1,
    devuelta: false,
  })) || [];

  const filteredUsuarios = usuarioOptions.filter((opt) =>
    opt.label.toLowerCase().includes(searchUsuario.toLowerCase())
  );
  const filteredInsumos = insumoOptions.filter((opt) =>
    opt.label.toLowerCase().includes(searchInsumo.toLowerCase())
  );
  const filteredHerramientas = herramientaOptions.filter((opt) =>
    opt.label.toLowerCase().includes(searchHerramienta.toLowerCase())
  );

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    const payload = {
      ...actividad,
      usuarios: actividad.usuarios,
      insumos: actividad.insumos?.map((i) => ({
        insumo_id: i.insumo_id,
        cantidad_usada: i.cantidad_usada,
      })),
      herramientas: actividad.herramientas?.map((h) => ({
        herramienta_id: h.herramienta_id,
        cantidad_entregada: h.cantidad_entregada,
        entregada: h.entregada ?? true,
        devuelta: h.devuelta ?? false,
        fecha_devolucion: h.fecha_devolucion ?? null,
      })),
    };

    mutation.mutate(payload, {
      onSuccess: () => {
        setActividad({
          descripcion: "",
          fecha_inicio: "",
          fecha_fin: "",
          tipo_actividad_id: 0,
          cultivo_id: 0,
          estado: "PENDIENTE",
          prioridad: "MEDIA",
          instrucciones_adicionales: "",
          usuarios: [],
          insumos: [],
          herramientas: [],
        });
        navigate("/cultivo/listaractividad/");
      },
      onError: (error) => {
        console.error("Error al registrar la actividad:", error);
        // Aquí puedes agregar una notificación para el usuario, por ejemplo, con react-toastify
      },
    });
  };

  const handleInsumoCantidadChange = (value: number, index: number) => {
    const updatedInsumos = [...(actividad.insumos || [])];
    updatedInsumos[index] = { ...updatedInsumos[index], cantidad_usada: value };
    setActividad({ ...actividad, insumos: updatedInsumos });
  };

  const handleHerramientaCantidadChange = (value: number, index: number) => {
    const updatedHerramientas = [...(actividad.herramientas || [])];
    updatedHerramientas[index] = { ...updatedHerramientas[index], cantidad_entregada: value };
    setActividad({ ...actividad, herramientas: updatedHerramientas });
  };

  return (
    <DefaultLayout>
      <ModalHerramienta isOpen={openHerramientaModal} onOpenChange={setOpenHerramientaModal} />
      <ModalCultivo isOpen={openCultivoModal} onOpenChange={setOpenCultivoModal} />
      <ModalInsumo isOpen={openInsumoModal} onOpenChange={setOpenInsumoModal} />
      <ReuModal
        isOpen={openTipoActividadModal}
        onOpenChange={setOpenTipoActividadModal}
        title="Registrar Nuevo Tipo de Actividad"
        onConfirm={handleSubmitTipoActividad}
        confirmText="Guardar"
        cancelText="Cancelar"
      >
        <ReuInput
          label="Nombre"
          placeholder="Ingrese el nombre"
          type="text"
          value={nuevoTipoActividad.nombre}
          onChange={(e) => setNuevoTipoActividad({ ...nuevoTipoActividad, nombre: e.target.value })}
        />
        <ReuInput
          label="Descripción"
          placeholder="Ingrese la descripción"
          type="text"
          value={nuevoTipoActividad.descripcion}
          onChange={(e) => setNuevoTipoActividad({ ...nuevoTipoActividad, descripcion: e.target.value })}
        />
      </ReuModal>
      <Formulario
        title="Asignar actividad"
        onSubmit={handleSubmit}
        buttonText="Guardar Actividad"
        isSubmitting={mutation.isPending}
        className="bg-gray-50"
      >
        <div className="space-y-4">
          <ReuInput
            label="Descripción"
            placeholder="Ingrese la descripción"
            type="text"
            value={actividad.descripcion}
            onChange={(e) => setActividad({ ...actividad, descripcion: e.target.value })}
          />

          <label className="block text-sm font-medium text-gray-700">Fecha de inicio</label>
          <ReuInput
            type="datetime-local"
            value={actividad.fecha_inicio}
            onChange={(e) => setActividad({ ...actividad, fecha_inicio: e.target.value })}
          />

          <label className="block text-sm font-medium text-gray-700">Fecha de fin</label>
          <ReuInput
            type="datetime-local"
            value={actividad.fecha_fin}
            onChange={(e) => setActividad({ ...actividad, fecha_fin: e.target.value })}
          />

          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700">Estado</label>
              <select
                name="estado"
                value={actividad.estado}
                onChange={(e) =>
                  setActividad({ ...actividad, estado: e.target.value as Actividad['estado'] })
                }
                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border"
              >
                <option value="PENDIENTE">Pendiente</option>
                <option value="EN_PROCESO">En proceso</option>
                <option value="COMPLETADA">Completada</option>
                <option value="CANCELADA">Cancelada</option>
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700">Prioridad</label>
              <select
                name="prioridad"
                value={actividad.prioridad}
                onChange={(e) =>
                  setActividad({ ...actividad, prioridad: e.target.value as Actividad['prioridad'] })
                }
                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border"
              >
                <option value="ALTA">Alta</option>
                <option value="MEDIA">Media</option>
                <option value="BAJA">Baja</option>
              </select>
            </div>
          </div>

          <div>
            <div className="flex items-center gap-2 mb-1">
              <label className="block text-sm font-medium text-gray-700">Tipo de Actividad</label>
              <button
                className="p-1 h-6 w-6 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                onClick={() => setOpenTipoActividadModal(true)}
                type="button"
              >
                <Plus className="h-4 w-4" />
              </button>
            </div>
            <select
              name="tipo_actividad_id"
              value={actividad.tipo_actividad_id || ""}
              onChange={(e) => setActividad({ ...actividad, tipo_actividad_id: Number(e.target.value) })}
              className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border"
            >
              <option value="">Seleccione un tipo de actividad</option>
              {tiposActividad?.map((tipo) => (
                <option key={tipo.id} value={tipo.id}>
                  {tipo.nombre}
                </option>
              ))}
            </select>
          </div>

          <div>
            <div className="flex items-center gap-2 mb-1">
              <label className="block text-sm font-medium text-gray-700">Cultivo</label>
              <button
                className="p-1 h-6 w-6 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                onClick={() => setOpenCultivoModal(true)}
                type="button"
              >
                <Plus className="h-4 w-4" />
              </button>
            </div>
            <select
              name="cultivo_id"
              value={actividad.cultivo_id || ""}
              onChange={(e) => setActividad({ ...actividad, cultivo_id: Number(e.target.value) })}
              className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border"
            >
              <option value="">Seleccione un cultivo</option>
              {cultivos?.map((cultivo) => (
                <option key={cultivo.id} value={cultivo.id}>
                  {cultivo.nombre}
                </option>
              ))}
            </select>
          </div>
        </div>

        <div className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Asignar a Usuarios</label>
            <Select
              isMulti
              options={filteredUsuarios}
              value={usuarioOptions.filter((opt) => actividad.usuarios.includes(opt.value))}
              onChange={(selected) =>
                setActividad({ ...actividad, usuarios: selected.map((s) => s.value) })
              }
              onInputChange={setSearchUsuario}
              placeholder="Buscar usuarios..."
              components={animatedComponents}
              className="basic-multi-select"
              classNamePrefix="select"
              noOptionsMessage={() => "No hay usuarios disponibles"}
            />
          </div>

          <div>
            <div className="flex items-center gap-2 mb-1">
              <label className="block text-sm font-medium text-gray-700">Insumos Requeridos</label>
              <button
                className="p-1 h-6 w-6 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                onClick={() => setOpenInsumoModal(true)}
                type="button"
              >
                <Plus className="h-4 w-4" />
              </button>
            </div>
            <Select
              isMulti
              options={filteredInsumos}
              value={actividad.insumos?.map((i) => ({
                value: i.insumo_id,
                label: insumoOptions.find((opt) => opt.value === i.insumo_id)?.label || "",
                cantidad: i.cantidad_usada,
              }))}
              onChange={(selected) =>
                setActividad({
                  ...actividad,
                  insumos: selected.map((s) => ({
                    insumo_id: s.value,
                    cantidad_usada: s.cantidad || 0,
                  })),
                })
              }
              onInputChange={setSearchInsumo}
              placeholder="Buscar insumos..."
              components={animatedComponents}
              className="basic-multi-select"
              classNamePrefix="select"
              noOptionsMessage={() => "No hay insumos disponibles"}
            />

            {actividad.insumos && actividad.insumos.length > 0 && (
              <div className="mt-2 space-y-2">
                {actividad.insumos.map((insumo, index) => (
                  <div key={insumo.insumo_id} className="flex items-center justify-between bg-gray-50 p-2 rounded">
                    <span className="text-sm">
                      {insumoOptions.find((opt) => opt.value === insumo.insumo_id)?.label.split('(')[0]}
                    </span>
                    <input
                      type="number"
                      min="0"
                      value={insumo.cantidad_usada || 0}
                      onChange={(e) => handleInsumoCantidadChange(Number(e.target.value), index)}
                      className="w-20 px-2 py-1 border rounded text-sm"
                      placeholder="Cantidad"
                    />
                  </div>
                ))}
              </div>
            )}
          </div>

          <div>
            <div className="flex items-center gap-2 mb-1">
              <label className="block text-sm font-medium text-gray-700">Herramientas</label>
              <button
                className="p-1 h-6 w-6 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                onClick={() => setOpenHerramientaModal(true)}
                type="button"
              >
                <Plus className="h-4 w-4" />
              </button>
            </div>
            <Select
              isMulti
              options={filteredHerramientas}
              value={actividad.herramientas?.map((h) => ({
                value: h.herramienta_id,
                label: herramientaOptions.find((opt) => opt.value === h.herramienta_id)?.label || "",
                cantidad_entregada: h.cantidad_entregada,
                devuelta: h.devuelta,
              }))}
              onChange={(selected) =>
                setActividad({
                  ...actividad,
                  herramientas: selected.map((s) => ({
                    herramienta_id: s.value,
                    cantidad_entregada: s.cantidad_entregada || 1,
                    entregada: true,
                    devuelta: s.devuelta ?? false,
                    fecha_devolucion: null,
                  })),
                })
              }
              onInputChange={setSearchHerramienta}
              placeholder="Buscar herramientas..."
              components={animatedComponents}
              className="basic-multi-select"
              classNamePrefix="select"
              noOptionsMessage={() => "No hay herramientas disponibles"}
            />

            {actividad.herramientas && actividad.herramientas.length > 0 && (
              <div className="mt-2 space-y-2">
                {actividad.herramientas.map((herramienta, index) => (
                  <div
                    key={herramienta.herramienta_id}
                    className="flex items-center justify-between bg-gray-50 p-2 rounded"
                  >
                    <span className="text-sm">
                      {herramientaOptions.find((opt) => opt.value === herramienta.herramienta_id)?.label.split('(')[0]}
                    </span>
                    <div className="flex items-center gap-2">
                      <input
                        type="number"
                        min="1"
                        value={herramienta.cantidad_entregada || 1}
                        onChange={(e) => handleHerramientaCantidadChange(Number(e.target.value), index)}
                        className="w-20 px-2 py-1 border rounded text-sm"
                        placeholder="Cantidad"
                      />
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">Instrucciones Adicionales</label>
            <textarea
              name="instrucciones_adicionales"
              value={actividad.instrucciones_adicionales || ""}
              onChange={(e) => setActividad({ ...actividad, instrucciones_adicionales: e.target.value })}
              rows={3}
              className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border"
              placeholder="Ingrese instrucciones adicionales para la actividad"
            />
          </div>
        </div>
        <div className="col-span-1 md:col-span-2 flex justify-center">
          <button
            className="w-full max-w-md px-4 py-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm uppercase tracking-wide"
            onClick={() => navigate("/cultivo/listaractividad/")}
          >
            Listar actividades
          </button>
        </div>
      </Formulario>
    </DefaultLayout>
  );
};

export default ActividadPage;