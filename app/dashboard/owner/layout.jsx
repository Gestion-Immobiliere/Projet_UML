'use client';
import { useRouter } from 'next/navigation';
import OwnerSidebar from './Sidebar';
import { useState, useEffect } from 'react';
import Navbar from '@/components/dashboard/owner/Navbar';

export default function OwnerLayout({ children }) {
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
      <ProtectedOwnerLayout  nom={nom} prenom={prenom} email={email}>{children}</ProtectedOwnerLayout>
  );
}

function ProtectedOwnerLayout({ children, nom, prenom, email  }) {

  return (
    <div className="flex h-screen bg-gray-50">
      <OwnerSidebar />
      <div className="flex-1 flex flex-col overflow-hidden">
        <Navbar user={{ name: `${prenom} ${nom}`, email: email, role: 'owner' }} />
        <main className="flex-1 overflow-y-auto p-6">{children}</main>
      </div>
    </div>
  );
}