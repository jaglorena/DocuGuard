<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="w-full max-w-2xl bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold text-center mb-6 text-blue-600">Bienvenido, {{ auth()->user()->nombre }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700 text-lg">
            <div class="bg-blue-50 p-4 rounded shadow">
                <strong>Nombre:</strong>
                <p>{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded shadow">
                <strong>Correo:</strong>
                <p>{{ auth()->user()->correo }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded shadow">
                <strong>Rol:</strong>
                <p>{{ auth()->user()->rol }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded shadow">
                <strong>Fecha de acceso:</strong>
                <p>{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-8 text-center">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                Cerrar Sesión
            </button>
        </form>
    </div>

</body>
</html>
