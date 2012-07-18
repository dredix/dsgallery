@echo off

setlocal

set orig_path=I:\Pictures
set thumb_path=%cd%
set magick=C:\Utils\ImageMagick-6.7.8-3

if not exist %thumb_path% ( md %thumb_path% )

for /F "delims=" %%i in ('dir /s/b/a-d "%orig_path%\*.jpg"') do call :thumbify "%%i"

endlocal

goto:eof


:thumbify

set file_path=%~dp1
set new_path=%thumb_path%\%file_path:~12,1000%
set new_file=%new_path%%~nx1

if not exist "%new_path%" ( md "%new_path%" )

if not exist "%new_file%" ( %magick%\convert -define jpeg:size=300x300 %1 -thumbnail 150x150^^ -gravity center -extent 150x150 "%new_file%" )

goto:eof