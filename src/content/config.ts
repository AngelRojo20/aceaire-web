import { defineCollection, z } from "astro:content";

const proyectos = defineCollection({
  type: "content",
  schema: z.object({
    title: z.string(),
    client: z.string(),
    category: z.enum([
      "Industrial",
      "Comercial",
      "Retail",
      "Hospitalario",
      "Residencial",
      "Logistica",
    ]),
    year: z.number(),
    location: z.string(),
    size: z.string().optional(), // e.g., "12,000 m2" or "300 TR"
    coverImage: z.string().default("/images/project-placeholder.jpg"),
    gallery: z.array(z.string()).optional(),
    services: z.array(z.string()).optional(), // e.g. ["Diseño", "Suministro", "Instalación"]
    featured: z.boolean().default(false),
  }),
});

const productos = defineCollection({
  type: "data",
  schema: z.object({
    sku: z.string(),
    name: z.string(),
    brand: z.string(),
    category: z.string(),
    subcategory: z.string().optional(),
    description: z.string(),
    specs: z.record(z.string(), z.string().or(z.number())).optional(),
    images: z.array(z.string()).optional(),
    datasheetUrl: z.string().optional(),
    inStock: z.boolean().default(true),
  }),
});

const servicios = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string(),
    description: z.string(),
    icon: z.string().optional(),
  }),
});

export const collections = {
  proyectos,
  productos,
  servicios,
};
