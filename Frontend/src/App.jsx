import { useState, useEffect } from 'react'
import Login from './Login.jsx'
import SignUp from './SignUp.jsx'
import './App.css'

function App() {
  const [view, setView] = useState('login');
  const [zapatos, setZapatos] = useState([]);
  const [busqueda, setBusqueda] = useState("");
  const [form, setForm] = useState({ id: null, nombre: '', stock: '', precio: '' });
  
  // 1. Usar la variable de entorno para la URL de la API
  const API_URL = import.meta.env.VITE_API_URL;

  const fetchZapatos = async () => {
    try {
      const res = await fetch(API_URL);
      const data = await res.json();
      // 2. Acceder a .result porque así lo envía tu clase Response.php
      if (data.response) {
        setZapatos(data.result);
      }
    } catch (error) {
      console.error("Error conectando a la API:", error);
    }
  };

  useEffect(() => { 
    if (view === 'inventario') fetchZapatos(); 
  }, [view]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // 3. Ajuste de URL y Método para PHP
    // Para PUT/DELETE en PHP usualmente pasamos el ID como parámetro de consulta (?id=)
    const metodo = form.id ? 'PUT' : 'POST';
    const url = form.id ? `${API_URL}?id=${form.id}` : API_URL;

    try {
      const res = await fetch(url, {
        method: metodo,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
          nombre: form.nombre, 
          stock: form.stock, 
          precio: form.precio 
        })
      });
      
      const resData = await res.json();
      if(resData.response) {
        setForm({ id: null, nombre: '', stock: '', precio: '' });
        fetchZapatos();
      } else {
        alert(resData.message);
      }
    } catch (error) {
      console.error("Error al guardar:", error);
    }
  };

  const prepararEdicion = (zapato) => setForm(zapato);

  const eliminarZapato = async (id) => {
    if (window.confirm("¿Eliminar este zapato?")) {
      // 4. Se envía el ID por URL para que el backend lo capture con $_GET['id']
      await fetch(`${API_URL}?id=${id}`, { method: 'DELETE' });
      fetchZapatos();
    }
  };

  // 5. Ajuste de filtrado: En DBeaver tu columna es "Stock" (mayúscula) o "stock" 
  // Asegúrate de que coincida con lo que devuelve el JSON de PHP
  const zapatosFiltrados = zapatos.filter(z => 
    z.nombre.toLowerCase().includes(busqueda.toLowerCase())
  );

  // --- NAVEGACIÓN ---
  if (view === 'login') {
    return <Login onLoginSuccess={() => setView('inventario')} onGoToSignUp={() => setView('signup')} />;
  }

  if (view === 'signup') {
    return <SignUp onSignUpSuccess={() => setView('login')} onGoToLogin={() => setView('login')} />;
  }

  return (
    <div className="inventory-container">
      <header className="inventory-header">
        <h1>Gestión de Inventario</h1>
        <button className="btn-logout" onClick={() => setView('login')}>
          Cerrar Sesión
        </button>
      </header>

      <div className="form-card">
        <form className="inventory-form" onSubmit={handleSubmit}>
          <input type="text" placeholder="Nombre" value={form.nombre} onChange={e => setForm({...form, nombre: e.target.value})} required />
          <input type="number" placeholder="Stock" value={form.stock} onChange={e => setForm({...form, stock: e.target.value})} required />
          <input type="number" placeholder="Precio" value={form.precio} onChange={e => setForm({...form, precio: e.target.value})} required />
          
          {form.id ? (
            <button type="submit" className="btn-update">Actualizar</button>
          ) : (
            <button type="submit" className="btn-create">Crear</button>
          )}
        </form>
      </div>

      <input 
        type="text" 
        className="search-bar" 
        placeholder="Buscar por nombre..." 
        value={busqueda}
        onChange={(e) => setBusqueda(e.target.value)}
      />

      <table className="inventory-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Stock</th>
            <th>Precio</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {zapatosFiltrados.length > 0 ? (
            zapatosFiltrados.map(z => (
              <tr key={z.id}>
                <td>{z.nombre}</td>
                <td>{z.Stock || z.stock}</td> {/* Soporta ambas nomenclaturas */}
                <td>${z.precio}</td>
                <td>
                  <button className="btn-edit" onClick={() => prepararEdicion(z)}>Editar</button>
                  <button className="btn-delete" onClick={() => eliminarZapato(z.id)}>Eliminar</button>
                </td>
              </tr>
            ))
          ) : (
            <tr><td colSpan="4" style={{textAlign: 'center'}}>No se encontraron registros</td></tr>
          )}
        </tbody>
      </table>
    </div>
  );
}

export default App;

/*
import { useState, useEffect } from 'react';
import './App.css';

function App() {
  const [zapatos, setZapatos] = useState([]);
  const [busqueda, setBusqueda] = useState("");
  const [form, setForm] = useState({ id: null, nombre: '', stock: '', precio: '' });

  // URL de tu API
  const API_URL = "http://localhost:3000/zapatos";

  const fetchZapatos = async () => {
    const res = await fetch(API_URL);
    const data = await res.json();
    setZapatos(data);
  };

  useEffect(() => { fetchZapatos(); }, []);

  // Función para Crear o Actualizar
  const handleSubmit = async (e) => {
    e.preventDefault();
    const metodo = form.id ? 'PUT' : 'POST';
    const url = form.id ? `${API_URL}/${form.id}` : API_URL;

    await fetch(url, {
      method: metodo,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nombre: form.nombre, stock: form.stock, precio: form.precio })
    });

    setForm({ id: null, nombre: '', stock: '', precio: '' }); // Limpiar campos
    fetchZapatos(); // Recargar tabla
  };

  // Cargar datos en el formulario para editar
  const prepararEdicion = (zapato) => {
    setForm(zapato);
  };

  // Eliminar zapato
  const eliminarZapato = async (id) => {
    if (window.confirm("¿Eliminar este zapato?")) {
      await fetch(`${API_URL}/${id}`, { method: 'DELETE' });
      fetchZapatos();
    }
  };

  // Filtro para el buscador
  const zapatosFiltrados = zapatos.filter(z => 
    z.nombre.toLowerCase().includes(busqueda.toLowerCase())
  );

  return (
    <div className="inventory-container">
      <h1>Gestión de Inventario</h1>

      {/* FORMULARIO }
      <div className="form-card">
        <h3>{form.id ? "Actualizar Zapato" : "Nuevo Zapato"}</h3>
        <form className="inventory-form" onSubmit={handleSubmit}>
          <input type="text" placeholder="Nombre" value={form.nombre} onChange={e => setForm({...form, nombre: e.target.value})} required />
          <input type="number" placeholder="Stock" value={form.stock} onChange={e => setForm({...form, stock: e.target.value})} required />
          <input type="number" placeholder="Precio" value={form.precio} onChange={e => setForm({...form, precio: e.target.value})} required />
          
          {form.id ? (
            <button type="submit" className="btn-update">Actualizar</button>
          ) : (
            <button type="submit" className="btn-create">Crear</button>
          )}
        </form>
      </div>

      {/* BUSCADOR }
      <input 
        type="text" 
        className="search-bar" 
        placeholder="Buscar por nombre..." 
        value={busqueda}
        onChange={(e) => setBusqueda(e.target.value)}
      />

      {/* TABLA }
      <table className="inventory-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Stock</th>
            <th>Precio</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {zapatosFiltrados.map(z => (
            <tr key={z.id}>
              <td>{z.nombre}</td>
              <td>{z.stock}</td>
              <td>${z.precio}</td>
              <td>
                <button className="btn-edit" onClick={() => prepararEdicion(z)}>Editar</button>
                <button className="btn-delete" onClick={() => eliminarZapato(z.id)}>Eliminar</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default App;
*/