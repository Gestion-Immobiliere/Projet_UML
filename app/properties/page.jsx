
"use client";

import { useEffect, useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import {
  Search,
  Filter,
  MapPin,
  ChevronDown,
  BedDouble,
  Bath,
  Ruler,
  ArrowRight,
} from 'lucide-react';
import PropertyCard from '../../components/PropertyCard.jsx'

const API_BASE_URL = process.env.NEXT_PUBLIC_API_BASE_URL || 'http://localhost:8000';

const neighborhoods = [
  { name: 'Almadies', emoji: '🏝️' },
  { name: 'Plateau', emoji: '🏙️' },
  { name: 'Mermoz', emoji: '🌳' },
  { name: 'Fann', emoji: '🏛️' },
  { name: 'Ouakam', emoji: '🌊' },
  { name: 'Ngor', emoji: '🏖️' },
  { name: 'Yoff', emoji: '🐟' },
  { name: 'Sicap', emoji: '🏘️' },
];

export default function PropertiesPage() {
  const [properties, setProperties] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [filters, setFilters] = useState({
    type: '',
    minPrice: '',
    bedrooms: '',
    neighborhood: '',
    statut: '',
    sort: 'relevance',
  });
  const [showFilters, setShowFilters] = useState(false);
  const [viewMode, setViewMode] = useState('grid');

  const fetchProperties = async (search, filters) => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      const localisation = filters.neighborhood || search || '';
      if (localisation) params.append('localisation', localisation);
      if (filters.type) params.append('type', filters.type);
      if (filters.minPrice && !isNaN(filters.minPrice) && filters.minPrice > 0) {
        params.append('montant', filters.minPrice);
      }
      if (filters.bedrooms && !isNaN(filters.bedrooms) && filters.bedrooms > 0) {
        params.append('nombreChambres', filters.bedrooms);
      }
      if (filters.statut) params.append('statut', filters.statut);

      const url = `${API_BASE_URL}/api/biens-publics/filter?${params}`;
      console.log('Fetching:', url);
      const res = await fetch(url, { cache: 'no-store' });
      const data = await res.json();
      console.log('API Response:', data);

      if (!res.ok) {
        throw new Error(`Erreur ${res.status}: ${data.message || res.statusText}`);
      }

      let sortedData = [...data];
      if (filters.sort === 'priceAsc') {
        sortedData.sort((a, b) => a.montant - b.montant);
      } else if (filters.sort === 'priceDesc') {
        sortedData.sort((a, b) => b.montant - a.montant);
      } else if (filters.sort === 'surface') {
        sortedData.sort((a, b) => b.surface - a.surface);
      }

      console.log('Setting Properties:', sortedData);
      setProperties(sortedData);
      setLoading(false);
    } catch (err) {
      console.error('Fetch Error:', err);
      setError('Impossible de charger les propriétés. Veuillez réessayer.');
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchProperties(searchTerm, filters);
  }, [searchTerm, filters]);

  useEffect(() => {
    console.log('Updated Properties:', properties);
  }, [properties]);

  const formatPrice = (price) => {
    if (!price) return 'N/A';
    return new Intl.NumberFormat('fr-FR', { currency: 'XOF' }).format(price) + ' FCFA';
  };

  const handleResetFilters = () => {
    setSearchTerm('');
    setFilters({
      type: '',
      minPrice: '',
      bedrooms: '',
      neighborhood: '',
      statut: '',
      sort: 'relevance',
    });
  };

  return (
    <main className="overflow-hidden min-h-screen bg-[#f9f5f0]">
      {/* Header Section */}
      <section className="relative bg-[#8d7364] text-white py-20">
        <div className="absolute inset-0 bg-[url('/dakar-pattern.png')] opacity-10"></div>
        <div className="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
          <h1 className="text-4xl md:text-5xl font-bold mb-4">Trouvez Votre Propriété Idéale à Dakar</h1>
          <p className="text-xl text-[#e8d5b5] max-w-2xl mx-auto">
            Explorez notre sélection exclusive de biens immobiliers dans les meilleurs quartiers de Dakar
          </p>
        </div>
      </section>

      {/* Filter Section */}
      <section className="container mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
        <div className="bg-white rounded-xl shadow-lg p-6">
          <div className="flex flex-col md:flex-row gap-4 mb-4">
            <div className="relative flex-grow">
              <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Search className="h-5 w-5 text-[#8d7364]" />
              </div>
              <input
                type="text"
                placeholder="Rechercher 'Almadies', 'Appartement', 'Villa'..."
                className="block w-full pl-10 pr-4 py-3 border border-[#e0d6cc] rounded-lg bg-white focus:ring-2 focus:ring-[#8d7364] focus:border-transparent"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>
            <button
              onClick={() => setShowFilters(!showFilters)}
              className="flex items-center justify-center px-6 py-3 border border-[#e0d6cc] rounded-lg bg-white hover:bg-[#f5efe6] transition-colors"
            >
              <Filter className="h-5 w-5 mr-2 text-[#8d7364]" />
              <span className="text-[#5d4a3a]">Filtres</span>
              <ChevronDown className={`h-5 w-5 ml-2 text-[#8d7364] transition-transform ${showFilters ? 'rotate-180' : ''}`} />
            </button>
          </div>

          {showFilters && (
            <div className="bg-[#f5efe6] p-6 rounded-lg grid grid-cols-1 md:grid-cols-6 gap-6">
              <div>
                <label className="block text-sm font-medium text-[#5d4a3a] mb-2">Type de bien</label>
                <select
                  className="block w-full px-3 py-2 border border-[#e0d6cc] rounded-lg bg-white"
                  value={filters.type}
                  onChange={(e) => setFilters({ ...filters, type: e.target.value })}
                >
                  <option value="">Tous types</option>
                  <option value="villa">Villa</option>
                  <option value="appartement">Appartement</option>
                  <option value="maison">Maison</option>
                  <option value="studio">Studio</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-[#5d4a3a] mb-2">Quartier</label>
                <select
                  className="block w-full px-3 py-2 border border-[#e0d6cc] rounded-lg bg-white"
                  value={filters.neighborhood}
                  onChange={(e) => setFilters({ ...filters, neighborhood: e.target.value })}
                >
                  <option value="">Tous quartiers</option>
                  {neighborhoods.map((area) => (
                    <option key={area.name} value={area.name}>
                      {area.emoji} {area.name}
                    </option>
                  ))}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-[#5d4a3a] mb-2">Prix min (FCFA)</label>
                <input
                  type="number"
                  min="0"
                  className="block w-full px-3 py-2 border border-[#e0d6cc] rounded-lg bg-white"
                  placeholder="Minimum"
                  value={filters.minPrice}
                  onChange={(e) => {
                    const value = e.target.value ? parseFloat(e.target.value) : '';
                    if (value === '' || (value > 0 && !isNaN(value))) {
                      setFilters({ ...filters, minPrice: value });
                    }
                  }}
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-[#5d4a3a] mb-2">Chambres min</label>
                <select
                  className="block w-full px-3 py-2 border border-[#e0d6cc] rounded-lg bg-white"
                  value={filters.bedrooms}
                  onChange={(e) => {
                    const value = e.target.value ? parseInt(e.target.value) : '';
                    if (value === '' || (value > 0 && !isNaN(value))) {
                      setFilters({ ...filters, bedrooms: value });
                    }
                  }}
                >
                  <option value="">Toutes</option>
                  <option value="1">1+</option>
                  <option value="2">2+</option>
                  <option value="3">3+</option>
                  <option value="4">4+</option>
                  <option value="5">5+</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-[#5d4a3a] mb-2">Statut</label>
                <select
                  className="block w-full px-3 py-2 border border-[#e0d6cc] rounded-lg bg-white"
                  value={filters.statut}
                  onChange={(e) => setFilters({ ...filters, statut: e.target.value })}
                >
                  <option value="">Tous statuts</option>
                  <option value="disponible">Disponible</option>
                  <option value="vendu">Vendu</option>
                  <option value="loué">Loué</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-[#5d4a3a] mb-2">Trier par</label>
                <select
                  className="block w-full px-3 py-2 border border-[#e0d6cc] rounded-lg bg-white"
                  value={filters.sort}
                  onChange={(e) => setFilters({ ...filters, sort: e.target.value })}
                >
                  <option value="relevance">Pertinence</option>
                  <option value="priceAsc">Prix croissant</option>
                  <option value="priceDesc">Prix décroissant</option>
                  <option value="surface">Surface</option>
                </select>
              </div>
            </div>
          )}
        </div>
      </section>

      {/* Properties Section */}
      <section className="py-20 bg-white">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">Nos Propriétés</h2>
            <div className="w-24 h-1 bg-[#8d7364] mx-auto"></div>
            <p className="text-gray-600 mt-6 max-w-2xl mx-auto">
              Explorez notre catalogue complet de biens immobiliers à Dakar, filtrés selon vos critères.
            </p>
          </div>

          {/* View Toggle and Results Count */}
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div className="mb-4 md:mb-0">
              <h2 className="text-2xl font-semibold text-[#5d4a3a]">
                {properties.length} {properties.length > 1 ? 'propriétés trouvées' : 'propriété trouvée'}
              </h2>
              <p className="text-[#7a6652]">
                {searchTerm || filters.neighborhood
                  ? `Résultats pour "${searchTerm || filters.neighborhood}"`
                  : 'Nos dernières offres'}
              </p>
            </div>
            <div className="flex items-center space-x-4">
              <div className="flex items-center bg-white rounded-lg border border-[#e0d6cc]">
                <button
                  className={`px-4 py-2 text-sm ${viewMode === 'grid' ? 'bg-[#8d7364] text-white' : 'text-[#5d737a]'}`}
                  onClick={() => setViewMode('grid')}
                >
                  Grille
                </button>
                <button
                  className={`px-4 py-2 text-sm ${viewMode === 'list' ? 'bg-[#8d7364] text-white' : 'text-[#5d737a]'}`}
                  onClick={() => setViewMode('list')}
                >
                  Liste
                </button>
              </div>
            </div>
          </div>

          {loading ? (
            <p className="text-center py-16">Chargement...</p>
          ) : error ? (
            <p className="text-center py-16 text-red-600">{error}</p>
          ) : properties.length === 0 ? (
            <div className="text-center py-16 bg-white rounded-xl shadow-sm">
              <div className="mx-auto w-24 h-24 bg-[#f5efe6] rounded-full flex items-center justify-center mb-4">
                <MapPin className="h-12 w-12 text-[#8d7364]" />
              </div>
              <h3 className="text-xl font-medium text-[#5d737a] mb-2">Aucun résultat trouvé</h3>
              <p className="text-[#7a6652] max-w-md mx-auto">
                Essayez d'ajuster vos critères de recherche ou{' '}
                <button
                  className="text-[#8d7364] hover:underline"
                  onClick={handleResetFilters}
                >
                  réinitialiser les filtres
                </button>
              </p>
            </div>
          ) : (
            <>
              {viewMode === 'grid' ? (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                  {properties.map((property) => (
                    <PropertyCard
                      key={property.idImmobilier}
                      property={property}
                      formatPrice={formatPrice}
                    />
                  ))}
                </div>
              ) : (
                <div className="space-y-6">
                  {properties.map((property) => (
                    <div
                      key={property.idImmobilier}
                      className="bg-white rounded-xl shadow-sm overflow-hidden"
                    >
                      <div className="flex flex-col md:flex-row">
                        <div className="md:w-1/3 h-64 relative">
                          <Image
                            src={
                              property.images?.[0]?.chemin
                                ? `${API_BASE_URL}${property.images[0].chemin}`
                                : property.image || '/placeholder.jpg'
                            }
                            alt={property.titre || 'Propriété'}
                            fill
                            className="object-cover rounded-t-xl md:rounded-l-xl"
                            placeholder="blur"
                            blurDataURL="/placeholder.jpg"
                            sizes="(max-width: 768px) 100vw, 33vw"
                            quality={75}
                            loading="lazy"
                          />
                        </div>
                        <div className="md:w-2/3 p-6">
                          <div className="flex justify-between items-start">
                            <div>
                              <h3 className="text-xl font-semibold text-[#5d4a3a] truncate">
                                {property.titre || 'N/A'}
                              </h3>
                              <p className="text-[#7a6652] flex items-center mt-1">
                                <MapPin className="h-4 w-4 mr-1" />
                                {property.localisation?.split(',')[0] || 'N/A'}
                              </p>
                            </div>
                            <div className="text-2xl font-semibold text-[#8d7364]">
                              {formatPrice(property.montant)}
                            </div>
                          </div>
                          <div className="flex items-center mt-4 gap-4">
                            <div className="flex items-center text-[#7a6652]">
                              <BedDouble className="h-5 w-5 mr-2" />
                              <span>{property.nombreChambres || 'N/A'} chambres</span>
                            </div>
                            <div className="flex items-center text-[#7a6652]">
                              <Bath className="h-5 w-5 mr-2" />
                              <span>{property.nombreSalleBains || 'N/A'}</span>
                            </div>
                            <div className="flex items-center text-[#7a6652]">
                              <Ruler className="h-5 w-5 mr-2" />
                              <span>{property.surface ? `${property.surface} m²` : 'N/A'}</span>
                            </div>
                          </div>
                          <div className="mt-6">
                            <Link
                              href={`/properties/${property.idImmobilier}`}
                              className="inline-block bg-[#8d7364] text-white px-6 py-2 rounded-lg hover:bg-[#6b594e] transition-colors"
                            >
                              Voir les détails
                            </Link>
                          </div>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </>
          )}
        </div>
      </section>

      {/* Neighborhood Guide Section */}
      <section className="py-20 bg-[#f9f5f0]">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-4xl font-bold text-gray-900 mb-4">Guide des Quartiers de Dakar</h2>
          <div className="w-24 h-1 bg-[#8d7364] mx-auto mb-10"></div>
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
            {neighborhoods.map((area) => (
              <div
                key={area.name}
                className="border border-[#e0d6cc] rounded-lg p-4 hover:bg-[#f5efe6] transition-colors cursor-pointer"
                onClick={() => setFilters({ ...filters, neighborhood: area.name })}
              >
                <div className="text-2xl mb-2">{area.emoji}</div>
                <h4 className="font-medium text-[#5d4a3a]">{area.name}</h4>
              </div>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
}
