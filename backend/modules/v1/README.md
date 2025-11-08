# VZ Account API v1

API REST para operaciones con cuentas VZ. **No requiere autenticación**.

## Endpoints Disponibles

### 1. Obtener y Reclamar Cuenta

**Endpoint:** `GET /v1/vz-account/claim`

**Descripción:** Obtiene una cuenta disponible y la marca como "trabajando" para evitar conflictos de concurrencia.

**Características:**
- ✅ **SKIP LOCKED**: Evita bloqueos cuando múltiples procesos intentan obtener cuentas simultáneamente
- ✅ **Transaccional**: Operación atómica (todo o nada)
- ✅ **Sin autenticación**: Acceso directo sin tokens

**Respuesta Exitosa:**
```json
{
  "success": true,
  "message": "Cuenta obtenida exitosamente",
  "data": {
    "id": 123,
    "email": "user@example.com",
    "phone": "1234567890",
    "password": "password123",
    "marcador_status": "caliente",
    "depurador_no_pin_status": "trabajando",
    "depurador_no_pin_status_display": {
      "class": "label-warning",
      "icon": "fa-clock-o",
      "text": "Trabajando",
      "description": "Cuenta en proceso de depuración"
    },
    "marcador_notes": "Notas del marcador",
    "tracking_number": "TRK123456",
    "worker_user_id": null,
    "assigned_at": 1640995200,
    "depurador_no_pin_updated_at": 1640995200,
    "created_at": 1640995200,
    "updated_at": 1640995200
  }
}
```

**Respuesta Sin Cuentas Disponibles:**
```json
{
  "success": false,
  "message": "No hay cuentas disponibles para procesar",
  "data": null
}
```

### 2. Obtener Estadísticas

**Endpoint:** `GET /v1/vz-account/stats`

**Descripción:** Obtiene estadísticas de cuentas por estatus.

**Respuesta:**
```json
{
  "success": true,
  "message": "Estadísticas obtenidas exitosamente",
  "data": {
    "total": 1000,
    "with_status": 800,
    "without_status": 200,
    "in_process": 150,
    "pendiente": {
      "count": 300,
      "option": {
        "class": "label-primary",
        "icon": "fa-hourglass-half",
        "text": "Pendiente",
        "description": "Cuenta pendiente de procesar"
      }
    },
    "trabajando": {
      "count": 150,
      "option": {
        "class": "label-warning",
        "icon": "fa-clock-o",
        "text": "Trabajando",
        "description": "Cuenta en proceso de depuración"
      }
    },
    "hit": {
      "count": 200,
      "option": {
        "class": "label-success",
        "icon": "fa-check",
        "text": "Hit",
        "description": "Cuenta procesada exitosamente"
      }
    },
    "failed": {
      "count": 150,
      "option": {
        "class": "label-danger",
        "icon": "fa-times",
        "text": "Failed",
        "description": "Error en el procesamiento"
      }
    }
  }
}
```

### 3. Obtener Estadísticas de Cuentas Procesadas

**Endpoint:** `GET /v1/vz-account/processed-stats`

**Descripción:** Obtiene estadísticas de cuentas que han sido procesadas (estatus 'hit').

**Respuesta:**
```json
{
  "success": true,
  "message": "Estadísticas de cuentas procesadas obtenidas exitosamente",
  "data": {
    "total": 500,
    "pendiente": 200,
    "badpass": 150,
    "usado": 150,
    "pendiente_display": {
      "class": "label-primary",
      "icon": "fa-hourglass-half",
      "text": "Pendiente",
      "description": "Cuenta pendiente de verificación"
    },
    "badpass_display": {
      "class": "label-danger",
      "icon": "fa-times",
      "text": "Contraseña Incorrecta",
      "description": "La contraseña no es válida"
    },
    "usado_display": {
      "class": "label-success",
      "icon": "fa-check",
      "text": "Usado",
      "description": "Cuenta utilizada exitosamente"
    }
  }
}
```

### 4. Actualizar Estatus de Cuenta

**Endpoint:** `POST /v1/vz-account/update-status`

**Descripción:** Actualiza el estatus de una cuenta específica. **Importante:** Cuando el estatus se actualiza a `hit`, la cuenta se guarda automáticamente en la tabla `vz_no_pin_accounts` para seguimiento.

**Parámetros:**
- `account_id` (int, requerido): ID de la cuenta
- `status` (string, requerido): Nuevo estatus

**Estatus Válidos:**
- `pendiente`
- `trabajando`
- `hit` (guarda automáticamente en tabla de cuentas procesadas)
- `failed`

**Ejemplo de Request:**
```json
{
  "account_id": 123,
  "status": "hit"
}
```

**Respuesta Exitosa:**
```json
{
  "success": true,
  "message": "Status actualizado exitosamente",
  "data": {
    "id": 123,
    "depurador_no_pin_status": "hit",
    "depurador_no_pin_status_display": {
      "class": "label-success",
      "icon": "fa-check",
      "text": "Hit",
      "description": "Cuenta procesada exitosamente"
    },
    "depurador_no_pin_updated_at": 1640995200
  }
}
```

## Características Técnicas

### 🔒 Concurrencia y Bloqueos

**SKIP LOCKED Implementation:**
```sql
SELECT * FROM vz_account 
WHERE (depurador_no_pin_status IS NULL OR depurador_no_pin_status = 'pendiente')
ORDER BY id ASC
LIMIT 1
FOR UPDATE SKIP LOCKED
```

**Beneficios:**
- ✅ **Sin bloqueos**: Si una cuenta está siendo procesada, se salta automáticamente
- ✅ **Alta concurrencia**: Múltiples procesos pueden trabajar simultáneamente
- ✅ **Eficiencia**: No espera por cuentas bloqueadas

### 🌐 CORS y Acceso

**Configuración CORS:**
- ✅ **Origen**: `*` (cualquier dominio)
- ✅ **Métodos**: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS
- ✅ **Headers**: Todos permitidos
- ✅ **Credenciales**: No requeridas

### 🔄 Transacciones

**Operaciones Atómicas:**
- ✅ **BEGIN TRANSACTION**: Inicia transacción
- ✅ **SELECT FOR UPDATE SKIP LOCKED**: Bloquea cuenta disponible
- ✅ **UPDATE**: Actualiza estatus
- ✅ **COMMIT/ROLLBACK**: Confirma o revierte cambios

### 📊 Manejo de Errores

**Tipos de Errores:**
- ✅ **Sin cuentas disponibles**: Retorna mensaje específico
- ✅ **Error de validación**: Incluye errores detallados
- ✅ **Error interno**: Logs en servidor, mensaje genérico al cliente
- ✅ **Debug mode**: Mensajes detallados en desarrollo

## Ejemplos de Uso

### JavaScript/Fetch
```javascript
// Obtener cuenta
const response = await fetch('/v1/vz-account/claim');
const result = await response.json();

if (result.success) {
    console.log('Cuenta obtenida:', result.data);
} else {
    console.log('Error:', result.message);
}

// Actualizar estatus
const updateResponse = await fetch('/v1/vz-account/update-status', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        account_id: 123,
        status: 'hit'
    })
});
```

### cURL
```bash
# Obtener cuenta
curl -X GET "http://localhost/v1/vz-account/claim"

# Obtener estadísticas
curl -X GET "http://localhost/v1/vz-account/stats"

# Actualizar estatus
curl -X POST "http://localhost/v1/vz-account/update-status" \
  -H "Content-Type: application/json" \
  -d '{"account_id": 123, "status": "hit"}'
```

### Python/Requests
```python
import requests

# Obtener cuenta
response = requests.get('http://localhost/v1/vz-account/claim')
result = response.json()

if result['success']:
    print('Cuenta obtenida:', result['data'])
else:
    print('Error:', result['message'])

# Actualizar estatus
update_response = requests.post('http://localhost/v1/vz-account/update-status', 
    json={'account_id': 123, 'status': 'hit'})
```

## Notas Importantes

1. **Sin Autenticación**: La API es pública, úsala con precaución
2. **SKIP LOCKED**: Requiere MySQL 8.0+ o PostgreSQL 9.5+
3. **Transacciones**: Todas las operaciones son atómicas
4. **Logs**: Errores se registran en logs del servidor
5. **CORS**: Configurado para acceso desde cualquier dominio
6. **JSON**: Todas las respuestas son en formato JSON
7. **UTF-8**: Soporte completo para caracteres especiales y emojis

## Troubleshooting

### Error: "No hay cuentas disponibles"
- **Causa**: Todas las cuentas están siendo procesadas o ya procesadas
- **Solución**: Esperar o verificar que hay cuentas con estatus NULL o 'pendiente'

### Error: "Status inválido"
- **Causa**: Estatus no reconocido
- **Solución**: Usar solo estatus válidos: pendiente, trabajando, hit, failed

### Error: "Cuenta no encontrada"
- **Causa**: ID de cuenta inexistente
- **Solución**: Verificar que el ID existe en la base de datos

### Error de Concurrencia
- **Causa**: Múltiples procesos intentando acceder a la misma cuenta
- **Solución**: SKIP LOCKED maneja esto automáticamente
