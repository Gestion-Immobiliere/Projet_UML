'use client';
import { useRouter } from 'next/navigation';
import TenantSidebar from './Sidebar';
import { useState, useEffect } from 'react';
import Navbar from '@/components/dashboard/tenant/Navbar';

export default function AdminLayout({ children }) {
  const[token, setToken] = useState(null);
  const[nom, setNom] = useState("");
  const[prenom, setPrenom] = useState("");
  const[email, setEmail] = useState("");

   useEffect(() => {
    setToken(sessionStorage.getItem('auth_token'));
  }, []);

  useEffect(() => {
    const getInfos = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/utilisateurs/profile', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept' : 'application/json'
          }
        });
        const data = await response.json();
        setNom(data.nom);
        setPrenom(data.prenom);
        setEmail(data.email);  
      } catch (error) {
        console.error("Erreur chargement agents:", error);
      }
    };
    if (token) {
      getInfos();
    }
  }, [token])


  return (
      <ProtectedAdminLayout  nom={nom} prenom={prenom} email={email}>{children}</ProtectedAdminLayout>
  );
}

function ProtectedAdminLayout({ children, nom, prenom, email }) {

  return (
    <div className="flex h-screen bg-gray-50">
      <TenantSidebar />
      <div className="flex-1 flex flex-col overflow-hidden">
        <Navbar user={{  name: `${prenom} ${nom}`, email: email, role: 'tenant' }} />
        <main className="flex-1 overflow-y-auto p-6">{children}</main>
      </div>
    </div>
  );
}