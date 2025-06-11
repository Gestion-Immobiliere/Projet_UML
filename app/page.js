'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import {
  ArrowRight,
  MapPin,
  BedDouble,
  Bath,
  Ruler,
  Home,
  Building,
  ShieldCheck,
  HeartHandshake,
} from 'lucide-react';
import HeroBanner from '@/components/HeroBanner';

// URL de base de l'API (peut être déplacé dans un fichier .env)
const API_BASE_URL = 'http://localhost:8000';

export default function HomePage() {
  const [properties, setProperties] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchProperties = async () => {
      try {
        const res = await fetch(`${API_BASE_URL}/api/biens-publics`);
        if (!res.ok) {
          throw new Error('Erreur lors de la récupération des biens');
        }
        const data = await res.json();
        setProperties(data);
        setLoading(false);
      } catch (err) {
        console.error('Erreur chargement biens :', err);
        setError('Impossible de charger les biens. Veuillez réessayer plus tard.');
        setLoading(false);
      }
    };

    fetchProperties();
  }, []);

  const stats = [
    { value: '500+', label: 'Propriétés disponibles' },
    { value: '95%', label: 'Clients satisfaits' },
    { value: '10+', label: 'Années d\'expérience' },
    { value: '24h', label: 'Support réactif' },
  ];

  const services = [
    {
      icon: <Home className="h-8 w-8" />,
      title: 'Vente Immobilière',
      description: 'Transaction sécurisée pour l\'achat ou la vente de votre bien',
    },
    {
      icon: <Building className="h-8 w-8" />,
      title: 'Gestion Locative',
      description: 'Gestion professionnelle de vos biens en location',
    },
    {
      icon: <ShieldCheck className="h-8 w-8" />,
      title: 'Conseil en Investissement',
      description: 'Maximisez vos rendements avec nos experts',
    },
    {
      icon: <HeartHandshake className="h-8 w-8" />,
      title: 'Accompagnement Personnalisé',
      description: 'Un conseiller dédié à chaque client',
    },
  ];

  return (
    <main className="overflow-hidden">
      <HeroBanner
        title="L'excellence immobilière à Dakar"
        subtitle="Découvrez des propriétés d'exception adaptées à vos besoins"
        ctaText="Explorer nos biens"
      />

      <section className="py-16 bg-gradient-to-r from-[#f5efe6] to-[#e8d5b5]">
        <div className="container mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-6">
          {stats.map((stat, index) => (
            <div
              key={index}
              className="text-center p-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-sm"
            >
              <p className="text-3xl font-bold text-primary-600">{stat.value}</p>
              <p className="text-gray-600 mt-2">{stat.label}</p>
            </div>
          ))}
        </div>
      </section>

      <section className="py-20 bg-white">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">Nos propriétés phares</h2>
            <div className="w-24 h-1 bg-primary-600 mx-auto"></div>
            <p className="text-gray-600 mt-6 max-w-2xl mx-auto">
              Découvrez notre sélection exclusive de biens immobiliers à Dakar, soigneusement choisis
              pour leur qualité et leur emplacement.
            </p>
          </div>

          {loading ? (
            <p className="text-center">Chargement...</p>
          ) : error ? (
            <p className="text-center text-red-600">{error}</p>
          ) : properties.length === 0 ? (
            <p className="text-center">Aucune propriété disponible pour le moment.</p>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
              {properties.map((property) => (
                <PropertyCard key={property.idImmobilier} property={property} />
              ))}
            </div>
          )}

          <div className="text-center mt-12">
            <Link
              href="/properties"
              className="inline-flex items-center px-8 py-3 bg-primary-600 text-[#8d7364] rounded-lg font-medium hover:bg-primary-700 transition-colors shadow-lg hover:shadow-xl"
            >
              Voir toutes nos propriétés <ArrowRight className="ml-2 h-5 w-5" />
            </Link>
          </div>
        </div>
      </section>

      <section className="py-20 bg-[#f9f5f0]">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-4xl font-bold text-gray-900 mb-4">Pourquoi nous choisir ?</h2>
          <div className="w-24 h-1 bg-primary-600 mx-auto mb-10"></div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {services.map((service, i) => (
              <div
                key={i}
                className="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow"
              >
                <div className="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                  {service.icon}
                </div>
                <h3 className="text-xl font-semibold text-gray-900 mb-3">{service.title}</h3>
                <p className="text-gray-600">{service.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
}

function PropertyCard({ property }) {
  // Construction de l'URL de l'image avec le domaine de base
  const image = property.images?.[0]?.chemin
    ? `${API_BASE_URL}${property.images[0].chemin}`
    : '/placeholder.jpg';

  return (
    <div className="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 bg-white">
      <Link href={`/properties/${property.idImmobilier}`} className="block">
        <div className="relative h-60 overflow-hidden">
          <Image
            src={image}
            alt={property.titre}
            fill
            className="object-cover transition-transform duration-500 group-hover:scale-105"
            placeholder="blur"
            blurDataURL="/placeholder.jpg"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent" />
        </div>

        <div className="p-5">
          <div className="flex justify-between items-start mb-2">
            <h3 className="text-xl font-bold text-gray-900 line-clamp-1">{property.titre}</h3>
            <div className="flex items-center bg-primary-100 text-primary-600 px-2 py-1 rounded text-sm">
              <MapPin className="h-4 w-4 mr-1" />
              {property.localisation?.split(',')[0] || 'Localisation inconnue'}
            </div>
          </div>

          <div className="flex justify-between text-sm text-gray-600 mb-4">
            <span className="flex items-center">
              <BedDouble className="h-4 w-4 mr-1" /> {property.nombreChambres || 'N/A'}
            </span>
            <span className="flex items-center">
              <Bath className="h-4 w-4 mr-1" /> {property.nombreSalleBains || 'N/A'}
            </span>
            <span className="flex items-center">
              <Ruler className="h-4 w-4 mr-1" /> {property.surface || 'N/A'} m²
            </span>
          </div>

          <div className="flex justify-between items-center pt-4 border-t border-gray-100">
            <span className="text-lg font-bold text-primary-600">
              {Number(property.montant)?.toLocaleString('fr-FR') || 'N/A'} FCFA
            </span>
            <span className="text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded-full">
              {property.type || 'N/A'}
            </span>
          </div>
        </div>
      </Link>
    </div>
  );
}