# Prueba técnica desarrollador backend WAM 

![WAM](https://www.primerempleo.com/storage/covers/1f386531369dfff501242cbedab74255ad7bac41.png)

## Funcionalidades implementadas:

- Obtener un listado de reservas en formato CSV desde un servidor externo
- Mostrar las reservas en una interfaz web
- Permitir búsqueda por texto libre en cualquier campo
- Descargar el listado en formato JSON


## Arquitectura y patrones de diseño:

- **Patrón MVC:** Separación clara entre controladores, servicios y vistas
**Principios SOLID**:

  - **S** - Responsabilidad única: Cada clase tiene una única razón para cambiar
  - **O** - Abierto/Cerrado: Las clases son extensibles sin modificación
  - **L** - Sustitución de Liskov: Las clases derivadas podrían sustituir a las clases base
  - **I** - Segregación de interfaces: Se usan interfaces específicas en lugar de una grande
  - **D** - Inversión de dependencias: Las clases dependen de abstracciones, no de implementaciones