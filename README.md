# Prueba técnica desarrollador backend WAM

![WAM](https://www.primerempleo.com/storage/covers/1f386531369dfff501242cbedab74255ad7bac41.png)

## Funcionalidades implementadas:

- Obtener un listado de reservas en formato CSV desde un servidor externo
- Mostrar las reservas en una interfaz web
- Permitir búsqueda por texto libre en cualquier campo
- Descargar el listado en formato JSON

## Arquitectura y patrones de diseño:

- **Patrón MVC:** Separación clara entre controladores, servicios y vistas
- **Principios SOLID**:

    - **S** - Responsabilidad única: Cada clase tiene una única razón para cambiar
    - **O** - Abierto/Cerrado: Las clases son extensibles sin modificación
    - **L** - Sustitución de Liskov: Las clases derivadas podrían sustituir a las clases base
    - **I** - Segregación de interfaces: Se usan interfaces específicas en lugar de una grande
    - **D** - Inversión de dependencias: Las clases dependen de abstracciones, no de implementaciones

## Versiónes

- **PHP:** 8.2
- **Symfony:** 7.2
- **Boostrap:** 5

## Aclaraciones

1. Se debe declarar en el env los valores correctos para:

- RESERVAS_API_ENDPOINT
- RESERVAS_API_USER
- RESERVAS_API_PASS

2. Se añade bootstrap mediante el CDN y no mediante asset-mapper para facilitar el montage por parte del evaluador en su
   entorno de pruebas.
3. Aunque no se tenga conexión a base de datos se usa el repository para mantener la coherencia del MVC con la
   estructura establecida con Symfony para las consultas de información
4. Dado que es una prueba no se utiliza ningún sistema de autenticación
