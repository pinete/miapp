echo "🔧 Levantando Laravel Sail..."
./vendor/bin/sail up -d

echo "🎨 Iniciando Vite..."
./vendor/bin/sail npm run dev
