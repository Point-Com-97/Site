@echo off
cd public\assets\scss
echo Compiling SCSS to CSS...
sass main.scss:main.css --style compressed --no-source-map
echo Compilation complete!
pause
