@echo off

set DIR_BASE=C:\proyectos
set TOBA_KERNEL=%DIR_BASE%\toba_26_git
echo %TOBA_KERNEL%

set /p ENTORNO="Ingrese entorno master/develop: "

set TOBA_ENTORNO=%DIR_BASE%\toba_26_%ENTORNO%
echo %TOBA_ENTORNO%

mkdir %TOBA_ENTORNO%

set DIRACT=%CD%
cd %TOBA_KERNEL%

FOR /f "tokens=*" %%G IN ('dir /ad-h /b') DO (
   if %%G neq proyectos if %%G neq gestionar  (
     mklink /d %TOBA_ENTORNO%\%%G %TOBA_KERNEL%\%%G
   )
)

mkdir %TOBA_ENTORNO%\instalacion
mkdir %TOBA_ENTORNO%\proyectos

mklink /d %TOBA_ENTORNO%\proyectos\toba_editor %TOBA_KERNEL%\proyectos\toba_editor
mklink /d %TOBA_ENTORNO%\proyectos\toba_usuarios %TOBA_KERNEL%\proyectos\toba_usuarios
mklink /d %TOBA_ENTORNO%\proyectos\toba_referencia %TOBA_KERNEL%\proyectos\toba_referencia
mklink /d %TOBA_ENTORNO%\proyectos\toba_testing %TOBA_KERNEL%\proyectos\toba_testing
mklink /d %TOBA_ENTORNO%\proyectos\curso_instro %TOBA_KERNEL%\proyectos\curso_intro

SET TOBA_DIR=%TOBA_ENTORNO%
SET TOBA_INSTALACION_DIR=%TOBA_DIR%\instalacion
SET PATH=%TOBA_DIR%\bin;%PATH%

instalar

copy %TOBA_KERNEL%\instalar\ldap_usuarios_intervan.ini %TOBA_ENTORNO%\instalacion

cd %DIRACT%