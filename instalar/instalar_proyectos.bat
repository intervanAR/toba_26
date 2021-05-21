@echo off

set DIR_BASE=C:\proyectos
set TOBA_KERNEL=%DIR_BASE%\toba_26_git
echo %TOBA_KERNEL%

set /p ENTORNO="Ingrese entorno master/develop: "

set TOBA_ENTORNO=%DIR_BASE%\toba_26_%ENTORNO%
echo %TOBA_ENTORNO%

set DIRACT=%CD%
cd %TOBA_KERNEL%\gestionar

SET TOBA_DIR=%TOBA_ENTORNO%
SET TOBA_INSTALACION_DIR=%TOBA_DIR%\instalacion
SET PATH=%TOBA_DIR%\bin;%PATH%


FOR /f "tokens=*" %%P IN ('dir /ad-h /b') DO (
   mkdir %TOBA_ENTORNO%\proyectos\%%P
   mkdir %TOBA_ENTORNO%\proyectos\%%P\metadatos
   mkdir %TOBA_ENTORNO%\proyectos\%%P\metadatos\permisos
   mkdir %TOBA_ENTORNO%\proyectos\%%P\metadatos_compilados
   
   mklink /d %TOBA_ENTORNO%\proyectos\%%P\metadatos\componentes %TOBA_KERNEL%\gestionar\%%P\metadatos\componentes
   mklink /d %TOBA_ENTORNO%\proyectos\%%P\metadatos\tablas %TOBA_KERNEL%\gestionar\%%P\metadatos\tablas
   copy %TOBA_KERNEL%\gestionar\%%P\metadatos\permisos\* %TOBA_ENTORNO%\proyectos\%%P\metadatos\permisos
   
   cd %%P
   
   FOR /f "tokens=*" %%D IN ('dir /ad-h /b') DO (
     if %%D neq metadatos if %%D neq metadatos_compilados  (
       mklink /d %TOBA_ENTORNO%\proyectos\%%P\%%D %TOBA_KERNEL%\gestionar\%%P\%%D   
     )
   )
   
   FOR /f "tokens=*" %%D IN ('dir /a-d-h /b') DO (
     if %%D neq metadatos if %%D neq metadatos_compilados  (
       mklink %TOBA_ENTORNO%\proyectos\%%P\%%D %TOBA_KERNEL%\gestionar\%%P\%%D   
     )
   )   
   
   cd ..
   
   echo "Instalar en"
   echo %TOBA_DIR%
   pause
   
   toba proyecto cargar -p %%P
   toba proyecto compilar -p %%P
)

cd %DIRACT%

