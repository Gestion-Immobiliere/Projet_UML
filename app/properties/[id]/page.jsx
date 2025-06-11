"use client";

import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import Image from "next/image";
import Link from "next/link";
import {
  ChevronLeft,
  ChevronRight,
  MapPin,
  BedDouble,
  Bath,
  Ruler,
  Heart,
  Phone,
  Mail,
  User,
} from "lucide-react";

// URL de base de l'API (peut être déplacé dans un fichier .env)
const API_BASE_URL = "http://localhost:8000";

export default function PropertyDetail() {
  const { id } = useParams();
  const [property, setProperty] = useState(null);
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [favorited, setFavorited] = useState(false);
  const [showContactForm, setShowContactForm] = useState(false);

  useEffect(() => {
    if (!id) return;
    const fetchProperty = async () => {
      try {
        const res = await fetch(`${API_BASE_URL}/api/biens-publics/${id}`);
        if (!res.ok) throw new Error("Bien non trouvé");
        const data = await res.json();
        setProperty(data);
        setLoading(false);
      } catch (err) {
        setError("Erreur lors du chargement de la propriété");
        console.error(err);
        setLoading(false);
      }
    };
    fetchProperty();
  }, [id]);

  const handlePrevImage = () => {
    setCurrentImageIndex((prev) => (prev === 0 ? images.length - 1 : prev - 1));
  };

  const handleNextImage = () => {
    setCurrentImageIndex((prev) => (prev === images.length - 1 ? 0 : prev + 1));
  };

  if (loading) {
    return <div className="text-center py-10 text-gray-600">Chargement...</div>;
  }

  if (!property || error) {
    return (
      <div className="text-center py-20 text-red-600">
        {error || "Propriété introuvable."}{" "}
        <Link href="/properties" className="underline text-primary-600">
          Retour
        </Link>
      </div>
    );
  }

  const images = property.images?.length > 0
    ? property.images.map((img) => `${API_BASE_URL}${img.chemin}`)
    : ["/placeholder.jpg"];

  const formatPrice = (montant) => {
    return new Intl.NumberFormat("fr-FR").format(montant) + " FCFA";
  };

  const auteur = property.auteur || {};

  return (
    <div className="max-w-6xl mx-auto p-6">
      <Link
        href="/properties"
        className="flex items-center text-sm text-gray-600 hover:text-gray-800 mb-4"
      >
        <ChevronLeft className="h-4 w-4 mr-1" /> Retour à la liste
      </Link>

      <h1 className="text-3xl font-bold text-gray-800 mb-2">{property.titre}</h1>
      <div className="text-sm text-gray-600 flex items-center mb-4">
        <MapPin className="h-4 w-4 mr-1" /> {property.localisation || "Localisation inconnue"}
      </div>

      <div className="relative mb-6 rounded-xl overflow-hidden">
        <div className="relative h-[350px] w-full">
          <Image
            src={images[currentImageIndex]}
            alt={`${property.titre} - Image ${currentImageIndex + 1}`}
            fill
            className="object-cover"
            placeholder="blur"
            blurDataURL="/placeholder.jpg"
            sizes="(max-width: 768px) 100vw, 1400px"
            quality={80}
          />
          <div className="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
            {images.map((_, index) => (
              <button
                key={index}
                className={`h-2 w-2 rounded-full ${index === currentImageIndex ? "bg-white" : "bg-white/50"}`}
                onClick={() => setCurrentImageIndex(index)}
              />
            ))}
          </div>
        </div>

        {images.length > 1 && (
          <>
            <button
              onClick={handlePrevImage}
              className="absolute left-3 top-1/2 transform -translate-y-1/2 bg-white/80 p-2 rounded-full shadow hover:bg-white transition-colors"
              aria-label="Image précédente"
            >
              <ChevronLeft className="h-6 w-6 text-gray-800" />
            </button>
            <button
              onClick={handleNextImage}
              className="absolute right-3 top-1/2 transform -translate-y-1/2 bg-white/80 p-2 rounded-full shadow hover:bg-white transition-colors"
              aria-label="Image suivante"
            >
              <ChevronRight className="h-6 w-6 text-gray-800" />
            </button>
          </>
        )}
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="md:col-span-2 space-y-6">
          <div>
            <h2 className="text-xl font-semibold mb-2">Description</h2>
            <p className="text-gray-700">{property.description || "Aucune description fournie."}</p>
          </div>

          <div>
            <h2 className="text-xl font-semibold mb-2">Détails</h2>
            <div className="grid grid-cols-2 gap-4 text-sm text-gray-700">
              <div className="flex items-center">
                <BedDouble className="w-4 h-4 mr-1" /> {property.nombreChambres || "N/A"} chambres
              </div>
              <div className="flex items-center">
                <Bath className="w-4 h-4 mr-1" /> {property.nombreSalleBains || "N/A"} salles de bain
              </div>
              <div className="flex items-center">
                <Ruler className="w-4 h-4 mr-1" /> {property.surface || "N/A"} m²
              </div>
              <div>
                Type : <span className="font-medium">{property.type || "N/A"}</span>
              </div>
              <div>
                Statut : <span className="font-medium">{property.statut || "N/A"}</span>
              </div>
              <div>Date : {property.datePublication || "N/A"}</div>
            </div>
          </div>
        </div>

        <div className="space-y-8">
          <div className="bg-white p-6 rounded-xl shadow space-y-4">
            <h2 className="text-xl font-semibold text-gray-800">Prix</h2>
            <p className="text-2xl font-bold text-primary-600">{formatPrice(property.montant)}</p>

            <button
              onClick={() => setFavorited(!favorited)}
              className={`flex items-center gap-2 px-4 py-2 rounded-lg border w-full transition-all ${
                favorited ? "border-red-500 text-red-500" : "border-gray-300 text-gray-600"
              }`}
            >
              <Heart className={`h-5 w-5 ${favorited ? "fill-current" : ""}`} />
              {favorited ? "Retirer des favoris" : "Ajouter aux favoris"}
            </button>
          </div>

          <div className="bg-white p-6 rounded-xl shadow">
            <h2 className="text-xl font-semibold text-gray-800 mb-4">Contacter l'agent</h2>
            <div className="flex items-center gap-4 p-4 bg-[#f9f5f0] rounded-lg mb-4">
              <div className="w-12 h-12 rounded-full bg-[#8d7364] flex items-center justify-center text-white">
                <User className="h-6 w-6" />
              </div>
              <div>
                <h4 className="font-medium text-[#5d4a3a]">
                  {auteur?.prenom || "N/A"} {auteur?.nom || ""}
                </h4>
                <p className="text-sm text-[#7a6652]">Agent immobilier</p>
              </div>
            </div>

            <button
              onClick={() => setShowContactForm(!showContactForm)}
              className="w-full bg-[#8d7364] hover:bg-[#6b594e] text-white py-3 rounded-lg font-medium transition-colors mb-4"
            >
              {showContactForm ? "Masquer le formulaire" : "Envoyer un message"}
            </button>

            {showContactForm && (
              <form className="space-y-4">
                <input
                  type="text"
                  placeholder="Votre nom"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  required
                />
                <input
                  type="email"
                  placeholder="Votre email"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  required
                />
                <textarea
                  placeholder="Votre message"
                  rows={4}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  defaultValue={`Je suis intéressé(e) par la propriété "${property.titre}"`}
                  required
                />
                <button
                  type="submit"
                  className="w-full bg-[#5d4a3a] hover:bg-[#4a3a2e] text-white py-3 rounded-lg font-medium transition-colors"
                >
                  Envoyer
                </button>
              </form>
            )}

            <div className="flex gap-2 mt-4">
              <a
                href={`tel:${auteur?.numTel || "#"}`}
                className="flex-1 flex items-center justify-center gap-2 bg-white border border-gray-300 text-[#5d4a3a] py-3 rounded-lg font-medium hover:bg-[#f5efe6] transition-colors"
              >
                <Phone className="h-5 w-5" /> Appeler
              </a>
              <a
                href={`mailto:${auteur?.adresseMail || "#"}`}
                className="flex-1 flex items-center justify-center gap-2 bg-white border border-gray-300 text-[#5d4a3a] py-3 rounded-lg font-medium hover:bg-[#f5efe6] transition-colors"
              >
                <Mail className="h-5 w-5" /> Email
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}