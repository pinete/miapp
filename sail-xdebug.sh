PORT=9876
echo "🔍 Verificando si el puerto $PORT está ocupado..."
PID=$(lsof -ti :$PORT)

if [ -n "$PID" ]; then
  echo "⚠️  Puerto $PORT ocupado por proceso PID $PID. Matando..."
  kill -9 $PID
  echo "✅ Puerto liberado."
else
  echo "✅ Puerto $PORT ya estaba libre."
fi

echo "🚀 Inicia el listener en VS Code ahora (puerto $PORT)"
read -p "Presiona ENTER cuando el listener esté activo..."

echo "🔄 Reiniciando Laravel Sail..."
./vendor/bin/sail down && ./vendor/bin/sail up -d

echo "✅ Entorno listo. Accede a la URL que activa el breakpoint."
