------------------------------------------------------------
--[1389]--  OBJETO - Editor FORM - EF (lista) 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'toba_editor', --proyecto
	'1389', --objeto
	NULL, --anterior
	NULL, --identificador
	'1', --reflexivo
	'toba', --clase_proyecto
	'toba_ei_formulario_ml', --clase
	'12', --punto_montaje
	'eiform_abm_detalle', --subclase
	'objetos_toba/eiform_abm_detalle.php', --subclase_archivo
	'toba', --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'OBJETO - Editor FORM - EF (lista)', --nombre
	NULL, --titulo
	'0', --colapsable
	'En esta interface se editan las propiedades de los elementos de formulario.', --descripcion
	'toba_editor', --fuente_datos_proyecto
	'instancia', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	NULL, --creacion
	'abajo'  --posicion_botonera
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_eventos
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'toba_editor', --proyecto
	'65', --evento_id
	'1389', --objeto
	'modificacion', --identificador
	'Modificacion', --etiqueta
	'1', --maneja_datos
	'0', --sobre_fila
	NULL, --confirmacion
	NULL, --estilo
	'apex', --imagen_recurso_origen
	NULL, --imagen
	'0', --en_botonera
	NULL, --ayuda
	'1', --orden
	NULL, --ci_predep
	'1', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	NULL, --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'toba_editor', --proyecto
	'66', --evento_id
	'1389', --objeto
	'seleccion', --identificador
	NULL, --etiqueta
	'1', --maneja_datos
	'1', --sobre_fila
	'', --confirmacion
	NULL, --estilo
	'apex', --imagen_recurso_origen
	'doc.gif', --imagen
	'0', --en_botonera
	'Seleccionar la fila', --ayuda
	'2', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	NULL, --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'toba_editor', --proyecto
	'1000375', --evento_id
	'1389', --objeto
	'mostrar_esquema', --identificador
	'Mostrar esquema cascadas', --etiqueta
	'1', --maneja_datos
	'0', --sobre_fila
	NULL, --confirmacion
	'ei-boton-izq', --estilo
	'apex', --imagen_recurso_origen
	'objetos/esquema.gif', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'3', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	NULL, --accion
	NULL, --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	'0', --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
--- FIN Grupo de desarrollo 1

------------------------------------------------------------
-- apex_objeto_ut_formulario
------------------------------------------------------------
INSERT INTO apex_objeto_ut_formulario (objeto_ut_formulario_proyecto, objeto_ut_formulario, tabla, titulo, ev_agregar, ev_agregar_etiq, ev_mod_modificar, ev_mod_modificar_etiq, ev_mod_eliminar, ev_mod_eliminar_etiq, ev_mod_limpiar, ev_mod_limpiar_etiq, ev_mod_clave, clase_proyecto, clase, auto_reset, ancho, ancho_etiqueta, expandir_descripcion, campo_bl, scroll, filas, filas_agregar, filas_agregar_online, filas_agregar_abajo, filas_agregar_texto, filas_borrar_en_linea, filas_undo, filas_ordenar, filas_ordenar_en_linea, columna_orden, filas_numerar, ev_seleccion, alto, analisis_cambios, no_imprimir_efs_sin_estado, resaltar_efs_con_estado, template, template_impresion) VALUES (
	'toba_editor', --objeto_ut_formulario_proyecto
	'1389', --objeto_ut_formulario
	'apex_objeto_ut_formulario_ef', --tabla
	'Propiedades del Elemento', --titulo
	NULL, --ev_agregar
	NULL, --ev_agregar_etiq
	NULL, --ev_mod_modificar
	NULL, --ev_mod_modificar_etiq
	'1', --ev_mod_eliminar
	NULL, --ev_mod_eliminar_etiq
	'1', --ev_mod_limpiar
	NULL, --ev_mod_limpiar_etiq
	'1', --ev_mod_clave
	NULL, --clase_proyecto
	NULL, --clase
	'1', --auto_reset
	'550', --ancho
	NULL, --ancho_etiqueta
	NULL, --expandir_descripcion
	NULL, --campo_bl
	'0', --scroll
	NULL, --filas
	'1', --filas_agregar
	'1', --filas_agregar_online
	'0', --filas_agregar_abajo
	NULL, --filas_agregar_texto
	'0', --filas_borrar_en_linea
	NULL, --filas_undo
	'1', --filas_ordenar
	'0', --filas_ordenar_en_linea
	NULL, --columna_orden
	'1', --filas_numerar
	'1', --ev_seleccion
	NULL, --alto
	'LINEA', --analisis_cambios
	NULL, --no_imprimir_efs_sin_estado
	NULL, --resaltar_efs_con_estado
	NULL, --template
	NULL  --template_impresion
);

------------------------------------------------------------
-- apex_objeto_ei_formulario_ef
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_ei_formulario_ef (objeto_ei_formulario_fila, objeto_ei_formulario, objeto_ei_formulario_proyecto, identificador, elemento_formulario, columnas, obligatorio, oculto_relaja_obligatorio, orden, etiqueta, etiqueta_estilo, descripcion, colapsado, desactivado, estilo, total, inicializacion, permitir_html, deshabilitar_rest_func, estado_defecto, solo_lectura, solo_lectura_modificacion, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_maestros, carga_cascada_relaj, cascada_mantiene_estado, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_filas, edit_columnas, edit_wrap, edit_resaltar, edit_ajustable, edit_confirmar_clave, edit_expreg, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, fieldset_fin, check_valor_si, check_valor_no, check_desc_si, check_desc_no, check_ml_toggle, fijo_sin_estado, editor_ancho, editor_alto, editor_botonera, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, upload_extensiones, punto_montaje) VALUES (
	'1249', --objeto_ei_formulario_fila
	'1389', --objeto_ei_formulario
	'toba_editor', --objeto_ei_formulario_proyecto
	'elemento_formulario', --identificador
	'ef_combo', --elemento_formulario
	'elemento_formulario', --columnas
	'0', --obligatorio
	'0', --oculto_relaja_obligatorio
	'5', --orden
	'Tipo de elemento', --etiqueta
	NULL, --etiqueta_estilo
	'Tipo de [wiki:Referencia/efs elemento de formulario]', --descripcion
	'0', --colapsado
	'0', --desactivado
	NULL, --estilo
	'0', --total
	NULL, --inicializacion
	NULL, --permitir_html
	NULL, --deshabilitar_rest_func
	'ef_editable', --estado_defecto
	'0', --solo_lectura
	'0', --solo_lectura_modificacion
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	'SELECT elemento_formulario, elemento_formulario, descripcion, parametros 
FROM apex_elemento_formulario
WHERE obsoleto=0
ORDER BY 2', --carga_sql
	'instancia', --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	NULL, --carga_maestros
	'0', --carga_cascada_relaj
	'0', --cascada_mantiene_estado
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_filas
	NULL, --edit_columnas
	NULL, --edit_wrap
	NULL, --edit_resaltar
	NULL, --edit_ajustable
	NULL, --edit_confirmar_clave
	NULL, --edit_expreg
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --fieldset_fin
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --check_ml_toggle
	NULL, --fijo_sin_estado
	NULL, --editor_ancho
	NULL, --editor_alto
	NULL, --editor_botonera
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL, --upload_extensiones
	'12'  --punto_montaje
);
INSERT INTO apex_objeto_ei_formulario_ef (objeto_ei_formulario_fila, objeto_ei_formulario, objeto_ei_formulario_proyecto, identificador, elemento_formulario, columnas, obligatorio, oculto_relaja_obligatorio, orden, etiqueta, etiqueta_estilo, descripcion, colapsado, desactivado, estilo, total, inicializacion, permitir_html, deshabilitar_rest_func, estado_defecto, solo_lectura, solo_lectura_modificacion, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_maestros, carga_cascada_relaj, cascada_mantiene_estado, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_filas, edit_columnas, edit_wrap, edit_resaltar, edit_ajustable, edit_confirmar_clave, edit_expreg, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, fieldset_fin, check_valor_si, check_valor_no, check_desc_si, check_desc_no, check_ml_toggle, fijo_sin_estado, editor_ancho, editor_alto, editor_botonera, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, upload_extensiones, punto_montaje) VALUES (
	'1250', --objeto_ei_formulario_fila
	'1389', --objeto_ei_formulario
	'toba_editor', --objeto_ei_formulario_proyecto
	'etiqueta', --identificador
	'ef_editable', --elemento_formulario
	'etiqueta', --columnas
	'0', --obligatorio
	'0', --oculto_relaja_obligatorio
	'2', --orden
	'Etiqueta', --etiqueta
	NULL, --etiqueta_estilo
	'Etiqueta para el formulario', --descripcion
	'0', --colapsado
	'0', --desactivado
	NULL, --estilo
	'0', --total
	NULL, --inicializacion
	'0', --permitir_html
	'0', --deshabilitar_rest_func
	NULL, --estado_defecto
	'0', --solo_lectura
	'0', --solo_lectura_modificacion
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	NULL, --carga_maestros
	NULL, --carga_cascada_relaj
	'0', --cascada_mantiene_estado
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	'25', --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_filas
	NULL, --edit_columnas
	NULL, --edit_wrap
	NULL, --edit_resaltar
	NULL, --edit_ajustable
	NULL, --edit_confirmar_clave
	NULL, --edit_expreg
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --fieldset_fin
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --check_ml_toggle
	NULL, --fijo_sin_estado
	NULL, --editor_ancho
	NULL, --editor_alto
	NULL, --editor_botonera
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL, --upload_extensiones
	'12'  --punto_montaje
);
INSERT INTO apex_objeto_ei_formulario_ef (objeto_ei_formulario_fila, objeto_ei_formulario, objeto_ei_formulario_proyecto, identificador, elemento_formulario, columnas, obligatorio, oculto_relaja_obligatorio, orden, etiqueta, etiqueta_estilo, descripcion, colapsado, desactivado, estilo, total, inicializacion, permitir_html, deshabilitar_rest_func, estado_defecto, solo_lectura, solo_lectura_modificacion, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_maestros, carga_cascada_relaj, cascada_mantiene_estado, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_filas, edit_columnas, edit_wrap, edit_resaltar, edit_ajustable, edit_confirmar_clave, edit_expreg, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, fieldset_fin, check_valor_si, check_valor_no, check_desc_si, check_desc_no, check_ml_toggle, fijo_sin_estado, editor_ancho, editor_alto, editor_botonera, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, upload_extensiones, punto_montaje) VALUES (
	'1251', --objeto_ei_formulario_fila
	'1389', --objeto_ei_formulario
	'toba_editor', --objeto_ei_formulario_proyecto
	'identificador', --identificador
	'ef_editable', --elemento_formulario
	'identificador', --columnas
	'1', --obligatorio
	'0', --oculto_relaja_obligatorio
	'1', --orden
	'Identificador', --etiqueta
	NULL, --etiqueta_estilo
	'ID del EF', --descripcion
	'0', --colapsado
	'0', --desactivado
	NULL, --estilo
	'0', --total
	NULL, --inicializacion
	'0', --permitir_html
	'0', --deshabilitar_rest_func
	NULL, --estado_defecto
	'0', --solo_lectura
	'0', --solo_lectura_modificacion
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	NULL, --carga_maestros
	NULL, --carga_cascada_relaj
	'0', --cascada_mantiene_estado
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	'20', --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_filas
	NULL, --edit_columnas
	NULL, --edit_wrap
	NULL, --edit_resaltar
	NULL, --edit_ajustable
	NULL, --edit_confirmar_clave
	'/^[a-zA-Z0-9_]+$/', --edit_expreg
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --fieldset_fin
	NULL, --check_valor_si
	NULL, --check_valor_no
	NULL, --check_desc_si
	NULL, --check_desc_no
	NULL, --check_ml_toggle
	NULL, --fijo_sin_estado
	NULL, --editor_ancho
	NULL, --editor_alto
	NULL, --editor_botonera
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL, --upload_extensiones
	'12'  --punto_montaje
);
INSERT INTO apex_objeto_ei_formulario_ef (objeto_ei_formulario_fila, objeto_ei_formulario, objeto_ei_formulario_proyecto, identificador, elemento_formulario, columnas, obligatorio, oculto_relaja_obligatorio, orden, etiqueta, etiqueta_estilo, descripcion, colapsado, desactivado, estilo, total, inicializacion, permitir_html, deshabilitar_rest_func, estado_defecto, solo_lectura, solo_lectura_modificacion, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_maestros, carga_cascada_relaj, cascada_mantiene_estado, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_filas, edit_columnas, edit_wrap, edit_resaltar, edit_ajustable, edit_confirmar_clave, edit_expreg, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, fieldset_fin, check_valor_si, check_valor_no, check_desc_si, check_desc_no, check_ml_toggle, fijo_sin_estado, editor_ancho, editor_alto, editor_botonera, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, upload_extensiones, punto_montaje) VALUES (
	'1252', --objeto_ei_formulario_fila
	'1389', --objeto_ei_formulario
	'toba_editor', --objeto_ei_formulario_proyecto
	'obligatorio', --identificador
	'ef_checkbox', --elemento_formulario
	'obligatorio', --columnas
	'0', --obligatorio
	'0', --oculto_relaja_obligatorio
	'3', --orden
	'Oblig', --etiqueta
	NULL, --etiqueta_estilo
	'Indicar si el campo es obligatorio', --descripcion
	'0', --colapsado
	'0', --desactivado
	NULL, --estilo
	'0', --total
	NULL, --inicializacion
	NULL, --permitir_html
	NULL, --deshabilitar_rest_func
	NULL, --estado_defecto
	'0', --solo_lectura
	'0', --solo_lectura_modificacion
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	NULL, --carga_maestros
	NULL, --carga_cascada_relaj
	'0', --cascada_mantiene_estado
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_filas
	NULL, --edit_columnas
	NULL, --edit_wrap
	NULL, --edit_resaltar
	NULL, --edit_ajustable
	NULL, --edit_confirmar_clave
	NULL, --edit_expreg
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --fieldset_fin
	'1', --check_valor_si
	'0', --check_valor_no
	'SI', --check_desc_si
	'No', --check_desc_no
	NULL, --check_ml_toggle
	NULL, --fijo_sin_estado
	NULL, --editor_ancho
	NULL, --editor_alto
	NULL, --editor_botonera
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL, --upload_extensiones
	'12'  --punto_montaje
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1
INSERT INTO apex_objeto_ei_formulario_ef (objeto_ei_formulario_fila, objeto_ei_formulario, objeto_ei_formulario_proyecto, identificador, elemento_formulario, columnas, obligatorio, oculto_relaja_obligatorio, orden, etiqueta, etiqueta_estilo, descripcion, colapsado, desactivado, estilo, total, inicializacion, permitir_html, deshabilitar_rest_func, estado_defecto, solo_lectura, solo_lectura_modificacion, carga_metodo, carga_clase, carga_include, carga_dt, carga_consulta_php, carga_sql, carga_fuente, carga_lista, carga_col_clave, carga_col_desc, carga_maestros, carga_cascada_relaj, cascada_mantiene_estado, carga_permite_no_seteado, carga_no_seteado, carga_no_seteado_ocultar, edit_tamano, edit_maximo, edit_mascara, edit_unidad, edit_rango, edit_filas, edit_columnas, edit_wrap, edit_resaltar, edit_ajustable, edit_confirmar_clave, edit_expreg, popup_item, popup_proyecto, popup_editable, popup_ventana, popup_carga_desc_metodo, popup_carga_desc_clase, popup_carga_desc_include, popup_puede_borrar_estado, fieldset_fin, check_valor_si, check_valor_no, check_desc_si, check_desc_no, check_ml_toggle, fijo_sin_estado, editor_ancho, editor_alto, editor_botonera, selec_cant_minima, selec_cant_maxima, selec_utilidades, selec_tamano, selec_ancho, selec_serializar, selec_cant_columnas, upload_extensiones, punto_montaje) VALUES (
	'1000327', --objeto_ei_formulario_fila
	'1389', --objeto_ei_formulario
	'toba_editor', --objeto_ei_formulario_proyecto
	'oculto_relaja_obligatorio', --identificador
	'ef_checkbox', --elemento_formulario
	'oculto_relaja_obligatorio', --columnas
	'0', --obligatorio
	'0', --oculto_relaja_obligatorio
	'4', --orden
	'Relajar oculto', --etiqueta
	NULL, --etiqueta_estilo
	'Cuando un campo obligatorio se oculta o se pone solo-lectura (por ejemplo utilizando la API javascript) se desactiva su validaci�n de obligatorio.', --descripcion
	'0', --colapsado
	'0', --desactivado
	NULL, --estilo
	'0', --total
	NULL, --inicializacion
	NULL, --permitir_html
	NULL, --deshabilitar_rest_func
	NULL, --estado_defecto
	'0', --solo_lectura
	'0', --solo_lectura_modificacion
	NULL, --carga_metodo
	NULL, --carga_clase
	NULL, --carga_include
	NULL, --carga_dt
	NULL, --carga_consulta_php
	NULL, --carga_sql
	NULL, --carga_fuente
	NULL, --carga_lista
	NULL, --carga_col_clave
	NULL, --carga_col_desc
	NULL, --carga_maestros
	NULL, --carga_cascada_relaj
	'0', --cascada_mantiene_estado
	'0', --carga_permite_no_seteado
	NULL, --carga_no_seteado
	NULL, --carga_no_seteado_ocultar
	NULL, --edit_tamano
	NULL, --edit_maximo
	NULL, --edit_mascara
	NULL, --edit_unidad
	NULL, --edit_rango
	NULL, --edit_filas
	NULL, --edit_columnas
	NULL, --edit_wrap
	NULL, --edit_resaltar
	NULL, --edit_ajustable
	NULL, --edit_confirmar_clave
	NULL, --edit_expreg
	NULL, --popup_item
	NULL, --popup_proyecto
	NULL, --popup_editable
	NULL, --popup_ventana
	NULL, --popup_carga_desc_metodo
	NULL, --popup_carga_desc_clase
	NULL, --popup_carga_desc_include
	NULL, --popup_puede_borrar_estado
	NULL, --fieldset_fin
	'1', --check_valor_si
	'0', --check_valor_no
	'S�', --check_desc_si
	'No', --check_desc_no
	NULL, --check_ml_toggle
	NULL, --fijo_sin_estado
	NULL, --editor_ancho
	NULL, --editor_alto
	NULL, --editor_botonera
	NULL, --selec_cant_minima
	NULL, --selec_cant_maxima
	NULL, --selec_utilidades
	NULL, --selec_tamano
	NULL, --selec_ancho
	NULL, --selec_serializar
	NULL, --selec_cant_columnas
	NULL, --upload_extensiones
	'12'  --punto_montaje
);
--- FIN Grupo de desarrollo 1
