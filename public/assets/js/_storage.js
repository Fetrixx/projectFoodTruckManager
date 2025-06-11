// Simulación de base de datos en localStorage
function initStorage() {
    if (!localStorage.getItem('foodtrucks')) {
        localStorage.setItem('foodtrucks', JSON.stringify([
            {
                id: 1,
                nombre: "Tacos El Güero",
                descripcion: "Los mejores tacos de la ciudad con receta familiar",
                rating: 4.5,
                ubicacion: "Frente al edificio principal",
                lat: -0.22985,
                lng: -78.52495,
                horarios: ["10:00", "10:30", "11:00", "11:30", "12:00"],
                menu: [
                    {nombre: "Taco Especial", precio: 3.99, descripcion: "Taco de pastor con piña"},
                    {nombre: "Quesadilla", precio: 4.50, descripcion: "Quesadilla de flor de calabaza"}
                ]
            },
            {
                id: 2,
                nombre: "Burger Paradise",
                descripcion: "Hamburguesas gourmet con ingredientes premium",
                rating: 4.7,
                ubicacion: "Junto a la fuente central",
                lat: -0.23000,
                lng: -78.52510,
                horarios: ["11:00", "11:30", "12:00", "12:30", "13:00"],
                menu: [
                    {nombre: "Clásica", precio: 6.99, descripcion: "Hamburguesa con queso y tocino"},
                    {nombre: "Veggie", precio: 7.50, descripcion: "Hamburguesa vegetariana con portobello"}
                ]
            },
            {
                id: 3,
                nombre: "Sushi Express",
                descripcion: "Sushi fresco preparado al momento",
                rating: 4.2,
                ubicacion: "Área de comida internacional",
                lat: -0.22970,
                lng: -78.52480,
                horarios: ["12:00", "12:30", "13:00", "13:30", "14:00"],
                menu: [
                    {nombre: "Roll California", precio: 8.99, descripcion: "Roll de cangrejo, pepino y aguacate"},
                    {nombre: "Sashimi Variado", precio: 12.50, descripcion: "Selección de 10 piezas de sashimi"}
                ]
            }
        ]));
    }
    
    if (!localStorage.getItem('users')) {
        localStorage.setItem('users', JSON.stringify([{
            username: "<?= VALID_USERNAME ?>",
            email: "<?= VALID_EMAIL ?>",
            favoritos: [1, 2],
            reservas: []
        }]));
    }
    
    if (!localStorage.getItem('reviews')) {
        localStorage.setItem('reviews', JSON.stringify([
            {
                foodtruckId: 1,
                foodtruck: "Tacos El Güero",
                rating: 5,
                comment: "¡Los mejores tacos que he probado! La salsa es increíble.",
                user: "Elias Medina",
                date: "15 de mayo, 2024"
            },
            {
                foodtruckId: 1,
                foodtruck: "Tacos El Güero",
                rating: 4,
                comment: "Buenos tacos, pero las porciones podrían ser más generosas.",
                user: "Ana López",
                date: "14 de mayo, 2024"
            },
            {
                foodtruckId: 2,
                foodtruck: "Burger Paradise",
                rating: 5,
                comment: "Las mejores hamburguesas de la ciudad. Las papas son espectaculares.",
                user: "Carlos Ramírez",
                date: "16 de mayo, 2024"
            },
            {
                foodtruckId: 2,
                foodtruck: "Burger Paradise",
                rating: 3,
                comment: "Buena calidad pero un poco caro para lo que ofrecen.",
                user: "María Fernández",
                date: "13 de mayo, 2024"
            },
            {
                foodtruckId: 3,
                foodtruck: "Sushi Express",
                rating: 4,
                comment: "Sushi fresco y bien preparado. Buen servicio.",
                user: "Juan Pérez",
                date: "12 de mayo, 2024"
            }
        ]));
    }
    
    if (!localStorage.getItem('reservas')) {
        localStorage.setItem('reservas', JSON.stringify([
            {
                id: 1001,
                foodtruckId: 1,
                foodtruckName: "Tacos El Güero",
                userId: "<?= VALID_USERNAME ?>",
                fecha: "2024-05-20",
                hora: "12:00",
                date: "20/5/2025, 12:00:00 PM",
                items: [
                    {nombre: "Taco Especial", cantidad: 3, precio: 3.99},
                    {nombre: "Quesadilla", cantidad: 2, precio: 4.50}
                ],
                total: 21.47,
                estado: "confirmada",
                status: "confirmada"
            },
            {
                id: 1002,
                foodtruckId: 2,
                foodtruckName: "Burger Paradise",
                userId: "usuario2",
                fecha: "2024-05-21",
                hora: "13:00",
                date: "21/5/2025, 13:00:00 PM",
                items: [
                    {nombre: "Clásica", cantidad: 1, precio: 6.99}
                ],
                total: 6.99,
                status: "pendiente"
            },
            {
                id: 1749083394173,
                foodtruckId: 1,
                foodtruckName: "Tacos El Güero",
                userId: "Elias",
                date: "6/4/2025, 9:29:54 PM",
                items: [
                    {
                    nombre: "Quesadilla",
                    precio: 4.5,
                    cantidad: 2,
                    descripcion: "Quesadilla de flor de calabaza"
                    }
                ],
                total: 9,
                status: "pendiente"
            }
        ]));
    }
}