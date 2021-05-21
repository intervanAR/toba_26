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
   if %%G neq instalar if %%G neq proyectos if %%G neq gestionar  (
     mklink /d %TOBA_ENTORNO%\%%G %TOBA_KERNEL%\%%G
   )
)

cd %DIRACT%
exit/b

mklink /d bin %TOBA_KERNEL%\bin
mklink /d exportaciones %TOBA_KERNEL%\exportaciones
mklink /d php %TOBA_KERNEL%\php
mklink /d temp %TOBA_KERNEL%\temp
mklink /d www %TOBA_KERNEL%\www

mkdir instalacion
mkdir proyectos

cd proyectos
mklink /d toba_editor %TOBA_KERNEL%\proyectos\toba_editor
mklink /d toba_usuarios %TOBA_KERNEL%\proyectos\toba_usuarios
mklink /d toba_referencia %TOBA_KERNEL%\proyectos\toba_referencia
mklink /d toba_testing %TOBA_KERNEL%\proyectos\toba_testing
mklink /d curso_instro %TOBA_KERNEL%\proyectos\curso_intro

