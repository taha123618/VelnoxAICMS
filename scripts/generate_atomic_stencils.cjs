const fs = require('fs');

function generateId() {
    return Math.random().toString(36).substring(2, 12);
}

function createEl(type, name, props = {}, children = []) {
    return {
        id: generateId(),
        type,
        canDrop: ['section', 'container', 'columns', 'grid', 'flexbox', 'stack'].includes(type),
        isLayoutElement: false,
        name,
        props: {
            styles: {
                desktop: { default: {}, hover: {} },
                tablet: { default: {}, hover: {} },
                mobile: { default: {}, hover: {} },
            },
            ...props
        },
        children
    };
}

function saveStencil(id, label, order, obj) {
    const stencilData = {
        id,
        label,
        sortOrder: order,
        image: '/assets/images/blocks/cta-ottawa.png',
        data: JSON.stringify(obj)
    };
    fs.writeFileSync(`Modules/Builder/resources/blocks/modern/${id}.json`, JSON.stringify(stencilData, null, 2));
}

// 1. CTA
const cta = createEl('section', 'CTA Section', {}, [
    createEl('container', 'Container', { styles: { desktop: { default: { backgroundColor: '#f9fafb', padding: { top: 60, bottom: 60, left: 40, right: 40, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 16, unit: 'px' } } } } }, [
        createEl('flexbox', 'CTA Content', { styles: { desktop: { default: { flexDirection: 'column', alignItems: 'center', textAlign: 'center', gap: { value: 16, unit: 'px' } } } } }, [
            createEl('heading', 'Title', { tag: 'h2', content: { innerText: 'Ready to dive in?' }, styles: { desktop: { default: { fontSize: { value: 36, unit: 'px' }, fontWeight: '800' } } } }),
            createEl('paragraph', 'Subtitle', { content: { innerText: 'Start your free trial today and experience the full power of our platform.' }, styles: { desktop: { default: { color: '#6b7280', fontSize: { value: 18, unit: 'px' } } } } }),
            createEl('flexbox', 'Buttons', { styles: { desktop: { default: { flexDirection: 'row', gap: { value: 16, unit: 'px' }, marginTop: { value: 16, unit: 'px' } } } } }, [
                createEl('button', 'Primary Button', { content: { innerText: 'Get Started' }, styles: { desktop: { default: { backgroundColor: '#4f46e5', color: '#ffffff', padding: { top: 12, bottom: 12, left: 24, right: 24, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 8, unit: 'px' } } } } }),
                createEl('button', 'Secondary Button', { content: { innerText: 'Learn More' }, styles: { desktop: { default: { backgroundColor: '#ffffff', color: '#4f46e5', border: { style: 'solid', color: '#4f46e5', width: 1, unit: 'px' }, padding: { top: 12, bottom: 12, left: 24, right: 24, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 8, unit: 'px' } } } } })
            ])
        ])
    ])
]);
saveStencil('cta', 'Modern CTA', 1, cta);

// 2. Hero
const hero = createEl('section', 'Hero Section', { styles: { desktop: { default: { padding: { top: 80, bottom: 80, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true } } } } }, [
    createEl('container', 'Hero Container', { styles: { desktop: { default: { display: 'flex', flexDirection: 'column', alignItems: 'center', textAlign: 'center' } } } }, [
        createEl('badge', 'Badge', { content: { innerText: 'New Feature Release' }, styles: { desktop: { default: { backgroundColor: '#eef2ff', color: '#4f46e5', padding: { top: 4, bottom: 4, left: 12, right: 12, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 9999, unit: 'px' }, fontSize: { value: 14, unit: 'px' }, fontWeight: '600' } } } }),
        createEl('heading', 'Main Headline', { tag: 'h1', content: { innerText: 'Build faster with our tools' }, styles: { desktop: { default: { fontSize: { value: 48, unit: 'px' }, fontWeight: '900', marginTop: { value: 24, unit: 'px' } } } } }),
        createEl('paragraph', 'Description', { content: { innerText: 'Empower your team with a suite of components designed for speed and flexibility.' }, styles: { desktop: { default: { fontSize: { value: 20, unit: 'px' }, color: '#4b5563', marginTop: { value: 16, unit: 'px' }, maxWidth: { value: 600, unit: 'px' } } } } }),
        createEl('flexbox', 'Action Area', { styles: { desktop: { default: { flexDirection: 'row', gap: { value: 16, unit: 'px' }, marginTop: { value: 32, unit: 'px' } } } } }, [
            createEl('button', 'Get Started', { content: { innerText: 'Get Started' }, styles: { desktop: { default: { backgroundColor: '#4f46e5', color: '#ffffff', padding: { top: 12, bottom: 12, left: 24, right: 24, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 8, unit: 'px' } } } } }),
            createEl('button', 'View Documentation', { content: { innerText: 'Documentation' }, styles: { desktop: { default: { backgroundColor: '#f3f4f6', color: '#111827', padding: { top: 12, bottom: 12, left: 24, right: 24, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 8, unit: 'px' } } } } })
        ])
    ])
]);
saveStencil('hero', 'Modern Hero', 2, hero);

// 3. Features
const features = createEl('section', 'Features Section', { styles: { desktop: { default: { padding: { top: 60, bottom: 60, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true } } } } }, [
    createEl('container', 'Container', {}, [
        createEl('flexbox', 'Header', { styles: { desktop: { default: { flexDirection: 'column', alignItems: 'center', textAlign: 'center', marginBottom: { value: 48, unit: 'px' } } } } }, [
            createEl('heading', 'Title', { tag: 'h2', content: { innerText: 'Why Choose Us' }, styles: { desktop: { default: { fontSize: { value: 36, unit: 'px' }, fontWeight: '800' } } } }),
            createEl('paragraph', 'Subtitle', { content: { innerText: 'Everything you need to scale your application.' }, styles: { desktop: { default: { color: '#6b7280', marginTop: { value: 8, unit: 'px' } } } } })
        ]),
        createEl('grid', 'Features Grid', { styles: { desktop: { default: { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: { value: 32, unit: 'px' } } } } }, [
            ...[1,2,3].map(i => createEl('flexbox', `Feature ${i}`, { styles: { desktop: { default: { flexDirection: 'column', alignItems: 'flex-start', gap: { value: 16, unit: 'px' } } } } }, [
                createEl('icon', 'Icon', { name: 'ph:star-fill', styles: { desktop: { default: { color: '#4f46e5', fontSize: { value: 32, unit: 'px' } } } } }),
                createEl('heading', 'Feature Name', { tag: 'h3', content: { innerText: `Feature ${i}` }, styles: { desktop: { default: { fontSize: { value: 20, unit: 'px' }, fontWeight: '700' } } } }),
                createEl('paragraph', 'Feature Desc', { content: { innerText: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' }, styles: { desktop: { default: { color: '#6b7280' } } } })
            ]))
        ])
    ])
]);
saveStencil('features', 'Modern Features', 3, features);

// 4. Products
const products = createEl('section', 'Products Section', { styles: { desktop: { default: { padding: { top: 60, bottom: 60, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true } } } } }, [
    createEl('container', 'Container', {}, [
        createEl('heading', 'Title', { tag: 'h2', content: { innerText: 'Featured Products' }, styles: { desktop: { default: { fontSize: { value: 36, unit: 'px' }, fontWeight: '800', textAlign: 'center', marginBottom: { value: 48, unit: 'px' } } } } }),
        createEl('grid', 'Products Grid', { styles: { desktop: { default: { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: { value: 24, unit: 'px' } } } } }, [
            ...[1,2,3].map(i => createEl('flexbox', `Product ${i}`, { styles: { desktop: { default: { flexDirection: 'column', backgroundColor: '#ffffff', border: { style: 'solid', color: '#e5e7eb', width: 1, unit: 'px' }, borderRadius: { value: 12, unit: 'px' }, overflow: 'hidden' } } } }, [
                createEl('image', 'Product Image', { src: 'https://via.placeholder.com/400x300', styles: { desktop: { default: { width: { value: 100, unit: '%' }, height: { value: 200, unit: 'px' }, objectFit: 'cover' } } } }),
                createEl('flexbox', 'Product Details', { styles: { desktop: { default: { padding: { top: 20, bottom: 20, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true }, flexDirection: 'column', gap: { value: 12, unit: 'px' } } } } }, [
                    createEl('heading', 'Product Name', { tag: 'h3', content: { innerText: `Premium Product ${i}` }, styles: { desktop: { default: { fontSize: { value: 20, unit: 'px' }, fontWeight: '700' } } } }),
                    createEl('paragraph', 'Product Desc', { content: { innerText: 'High quality materials and craftmanship.' }, styles: { desktop: { default: { color: '#6b7280', fontSize: { value: 14, unit: 'px' } } } } }),
                    createEl('flexbox', 'Price Row', { styles: { desktop: { default: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: { value: 8, unit: 'px' } } } } }, [
                        createEl('heading', 'Price', { tag: 'h4', content: { innerText: '$99.00' }, styles: { desktop: { default: { fontSize: { value: 24, unit: 'px' }, fontWeight: '800' } } } }),
                        createEl('button', 'Add to Cart', { content: { innerText: 'Add to Cart' }, styles: { desktop: { default: { backgroundColor: '#111827', color: '#ffffff', padding: { top: 8, bottom: 8, left: 16, right: 16, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 6, unit: 'px' }, fontSize: { value: 14, unit: 'px' } } } } })
                    ])
                ])
            ]))
        ])
    ])
]);
saveStencil('products', 'Modern Products', 4, products);

// 5. Pricing
const pricing = createEl('section', 'Pricing Section', { styles: { desktop: { default: { padding: { top: 80, bottom: 80, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true }, backgroundColor: '#f9fafb' } } } }, [
    createEl('container', 'Container', {}, [
        createEl('flexbox', 'Header', { styles: { desktop: { default: { flexDirection: 'column', alignItems: 'center', textAlign: 'center', marginBottom: { value: 48, unit: 'px' } } } } }, [
            createEl('heading', 'Title', { tag: 'h2', content: { innerText: 'Simple, transparent pricing' }, styles: { desktop: { default: { fontSize: { value: 36, unit: 'px' }, fontWeight: '800' } } } }),
            createEl('paragraph', 'Subtitle', { content: { innerText: 'No hidden fees. Cancel anytime.' }, styles: { desktop: { default: { color: '#6b7280', marginTop: { value: 8, unit: 'px' } } } } })
        ]),
        createEl('grid', 'Pricing Grid', { styles: { desktop: { default: { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: { value: 32, unit: 'px' }, alignItems: 'center' } } } }, [
            ...['Basic', 'Pro', 'Enterprise'].map((tier, i) => createEl('flexbox', `Tier ${tier}`, { styles: { desktop: { default: { flexDirection: 'column', backgroundColor: i === 1 ? '#111827' : '#ffffff', color: i === 1 ? '#ffffff' : '#111827', padding: { top: 32, bottom: 32, left: 32, right: 32, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 16, unit: 'px' }, border: { style: 'solid', color: '#e5e7eb', width: i === 1 ? 0 : 1, unit: 'px' }, transform: i === 1 ? 'scale(1.05)' : 'none', gap: { value: 24, unit: 'px' } } } } }, [
                createEl('heading', 'Tier Name', { tag: 'h3', content: { innerText: tier }, styles: { desktop: { default: { fontSize: { value: 20, unit: 'px' }, fontWeight: '600' } } } }),
                createEl('flexbox', 'Price Block', { styles: { desktop: { default: { flexDirection: 'row', alignItems: 'baseline', gap: { value: 4, unit: 'px' } } } } }, [
                    createEl('heading', 'Price', { tag: 'h4', content: { innerText: `$${i === 0 ? '19' : i === 1 ? '49' : '99'}` }, styles: { desktop: { default: { fontSize: { value: 48, unit: 'px' }, fontWeight: '900' } } } }),
                    createEl('paragraph', 'Period', { content: { innerText: '/mo' }, styles: { desktop: { default: { color: i === 1 ? '#9ca3af' : '#6b7280' } } } })
                ]),
                createEl('flexbox', 'Features List', { styles: { desktop: { default: { flexDirection: 'column', gap: { value: 12, unit: 'px' } } } } }, [
                    ...[1,2,3,4].map(f => createEl('flexbox', 'List Item', { styles: { desktop: { default: { flexDirection: 'row', alignItems: 'center', gap: { value: 8, unit: 'px' } } } } }, [
                        createEl('icon', 'Checkmark', { name: 'ph:check-circle-fill', styles: { desktop: { default: { color: i === 1 ? '#10b981' : '#4f46e5' } } } }),
                        createEl('paragraph', 'Feature Text', { content: { innerText: `Feature ${f} Included` }, styles: { desktop: { default: { color: i === 1 ? '#d1d5db' : '#4b5563', fontSize: { value: 14, unit: 'px' } } } } })
                    ]))
                ]),
                createEl('button', 'CTA Button', { content: { innerText: 'Get Started' }, styles: { desktop: { default: { backgroundColor: i === 1 ? '#4f46e5' : '#f3f4f6', color: i === 1 ? '#ffffff' : '#111827', width: { value: 100, unit: '%' }, padding: { top: 12, bottom: 12, left: 0, right: 0, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 8, unit: 'px' }, fontWeight: '600', marginTop: { value: 8, unit: 'px' } } } } })
            ]))
        ])
    ])
]);
saveStencil('pricing', 'Modern Pricing', 5, pricing);

// 6. Stats
const stats = createEl('section', 'Stats Section', { styles: { desktop: { default: { padding: { top: 60, bottom: 60, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true }, backgroundColor: '#111827' } } } }, [
    createEl('container', 'Container', {}, [
        createEl('grid', 'Stats Grid', { styles: { desktop: { default: { display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: { value: 24, unit: 'px' } } } } }, [
            ...[{label: 'Active Users', val: '100K+'}, {label: 'Downloads', val: '5M+'}, {label: 'Countries', val: '120+'}, {label: 'Uptime', val: '99.9%'}].map(s => createEl('flexbox', `Stat ${s.label}`, { styles: { desktop: { default: { flexDirection: 'column', alignItems: 'center', textAlign: 'center', gap: { value: 8, unit: 'px' } } } } }, [
                createEl('heading', 'Value', { tag: 'h3', content: { innerText: s.val }, styles: { desktop: { default: { fontSize: { value: 48, unit: 'px' }, fontWeight: '900', color: '#ffffff' } } } }),
                createEl('paragraph', 'Label', { content: { innerText: s.label }, styles: { desktop: { default: { color: '#9ca3af', fontSize: { value: 16, unit: 'px' }, textTransform: 'uppercase', letterSpacing: { value: 1, unit: 'px' } } } } })
            ]))
        ])
    ])
]);
saveStencil('stats', 'Modern Stats', 6, stats);

// 7. Blog Grid
const blogGrid = createEl('section', 'Blog Section', { styles: { desktop: { default: { padding: { top: 60, bottom: 60, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true } } } } }, [
    createEl('container', 'Container', {}, [
        createEl('flexbox', 'Header', { styles: { desktop: { default: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: { value: 48, unit: 'px' } } } } }, [
            createEl('flexbox', 'Titles', { styles: { desktop: { default: { flexDirection: 'column' } } } }, [
                createEl('heading', 'Title', { tag: 'h2', content: { innerText: 'Latest from the Blog' }, styles: { desktop: { default: { fontSize: { value: 36, unit: 'px' }, fontWeight: '800' } } } }),
                createEl('paragraph', 'Subtitle', { content: { innerText: 'Read our latest thoughts and insights.' }, styles: { desktop: { default: { color: '#6b7280', marginTop: { value: 8, unit: 'px' } } } } })
            ]),
            createEl('button', 'View All', { content: { innerText: 'View All Posts' }, styles: { desktop: { default: { backgroundColor: '#f3f4f6', color: '#111827', padding: { top: 8, bottom: 8, left: 16, right: 16, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 6, unit: 'px' } } } } })
        ]),
        createEl('grid', 'Blog Grid', { styles: { desktop: { default: { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: { value: 32, unit: 'px' } } } } }, [
            ...[1,2,3].map(i => createEl('flexbox', `Post ${i}`, { styles: { desktop: { default: { flexDirection: 'column', gap: { value: 16, unit: 'px' } } } } }, [
                createEl('image', 'Post Image', { src: 'https://via.placeholder.com/400x250', styles: { desktop: { default: { width: { value: 100, unit: '%' }, height: { value: 250, unit: 'px' }, objectFit: 'cover', borderRadius: { value: 12, unit: 'px' } } } } }),
                createEl('badge', 'Category', { content: { innerText: 'Technology' }, styles: { desktop: { default: { backgroundColor: '#eef2ff', color: '#4f46e5', padding: { top: 4, bottom: 4, left: 8, right: 8, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 4, unit: 'px' }, fontSize: { value: 12, unit: 'px' }, fontWeight: '600', alignSelf: 'flex-start' } } } }),
                createEl('heading', 'Post Title', { tag: 'h3', content: { innerText: `Blog Post Title ${i}` }, styles: { desktop: { default: { fontSize: { value: 20, unit: 'px' }, fontWeight: '700' } } } }),
                createEl('paragraph', 'Post Excerpt', { content: { innerText: 'This is a short excerpt describing the blog post content.' }, styles: { desktop: { default: { color: '#6b7280' } } } }),
                createEl('flexbox', 'Meta', { styles: { desktop: { default: { flexDirection: 'row', gap: { value: 16, unit: 'px' }, marginTop: { value: 8, unit: 'px' } } } } }, [
                    createEl('paragraph', 'Author', { content: { innerText: 'John Doe' }, styles: { desktop: { default: { color: '#374151', fontSize: { value: 14, unit: 'px' }, fontWeight: '500' } } } }),
                    createEl('paragraph', 'Date', { content: { innerText: 'Jul 17, 2026' }, styles: { desktop: { default: { color: '#9ca3af', fontSize: { value: 14, unit: 'px' } } } } })
                ])
            ]))
        ])
    ])
]);
saveStencil('blog-grid', 'Modern Blog Grid', 7, blogGrid);

// 8. Contact Form
const contactForm = createEl('section', 'Contact Section', { styles: { desktop: { default: { padding: { top: 80, bottom: 80, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true }, backgroundColor: '#ffffff' } } } }, [
    createEl('container', 'Container', { styles: { desktop: { default: { maxWidth: { value: 600, unit: 'px' }, margin: { top: 0, right: 'auto', bottom: 0, left: 'auto', unit: 'custom', isFourWay: true, hasUnit: true } } } } }, [
        createEl('flexbox', 'Header', { styles: { desktop: { default: { flexDirection: 'column', alignItems: 'center', textAlign: 'center', marginBottom: { value: 32, unit: 'px' } } } } }, [
            createEl('heading', 'Title', { tag: 'h2', content: { innerText: 'Get in Touch' }, styles: { desktop: { default: { fontSize: { value: 36, unit: 'px' }, fontWeight: '800' } } } }),
            createEl('paragraph', 'Subtitle', { content: { innerText: 'Fill out the form below and we will get back to you.' }, styles: { desktop: { default: { color: '#6b7280', marginTop: { value: 8, unit: 'px' } } } } })
        ]),
        createEl('form', 'Contact Form', { styles: { desktop: { default: { display: 'flex', flexDirection: 'column', gap: { value: 16, unit: 'px' } } } } }, [
            createEl('flexbox', 'Row', { styles: { desktop: { default: { flexDirection: 'row', gap: { value: 16, unit: 'px' } } } } }, [
                createEl('form-input', 'First Name', { placeholder: 'First Name', styles: { desktop: { default: { flex: 1 } } } }),
                createEl('form-input', 'Last Name', { placeholder: 'Last Name', styles: { desktop: { default: { flex: 1 } } } })
            ]),
            createEl('form-input', 'Email', { placeholder: 'Email Address', type: 'email' }),
            createEl('form-textarea', 'Message', { placeholder: 'Your Message', rows: 4 }),
            createEl('button', 'Submit', { content: { innerText: 'Send Message' }, styles: { desktop: { default: { backgroundColor: '#4f46e5', color: '#ffffff', padding: { top: 12, bottom: 12, left: 24, right: 24, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 8, unit: 'px' }, marginTop: { value: 8, unit: 'px' } } } } })
        ])
    ])
]);
saveStencil('contact-form', 'Modern Contact', 8, contactForm);

// 9. Testimonials
const testimonials = createEl('section', 'Testimonials Section', { styles: { desktop: { default: { padding: { top: 80, bottom: 80, left: 20, right: 20, unit: 'px', isFourWay: true, hasUnit: true }, backgroundColor: '#f9fafb' } } } }, [
    createEl('container', 'Container', {}, [
        createEl('heading', 'Title', { tag: 'h2', content: { innerText: 'What our customers say' }, styles: { desktop: { default: { fontSize: { value: 36, unit: 'px' }, fontWeight: '800', textAlign: 'center', marginBottom: { value: 48, unit: 'px' } } } } }),
        createEl('grid', 'Testimonials Grid', { styles: { desktop: { default: { display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: { value: 32, unit: 'px' } } } } }, [
            ...[1,2].map(i => createEl('flexbox', `Testimonial ${i}`, { styles: { desktop: { default: { flexDirection: 'column', backgroundColor: '#ffffff', padding: { top: 32, bottom: 32, left: 32, right: 32, unit: 'px', isFourWay: true, hasUnit: true }, borderRadius: { value: 16, unit: 'px' }, border: { style: 'solid', color: '#e5e7eb', width: 1, unit: 'px' }, gap: { value: 16, unit: 'px' } } } } }, [
                createEl('flexbox', 'Stars', { styles: { desktop: { default: { flexDirection: 'row', gap: { value: 4, unit: 'px' } } } } }, [
                    ...[1,2,3,4,5].map(() => createEl('icon', 'Star', { name: 'ph:star-fill', styles: { desktop: { default: { color: '#fbbf24', fontSize: { value: 20, unit: 'px' } } } } }))
                ]),
                createEl('paragraph', 'Quote', { content: { innerText: '"This platform has completely transformed how we build and deploy our web applications. The speed and flexibility are unmatched."' }, styles: { desktop: { default: { fontSize: { value: 18, unit: 'px' }, fontStyle: 'italic', color: '#111827' } } } }),
                createEl('flexbox', 'Author', { styles: { desktop: { default: { flexDirection: 'row', alignItems: 'center', gap: { value: 12, unit: 'px' }, marginTop: { value: 16, unit: 'px' } } } } }, [
                    createEl('image', 'Avatar', { src: 'https://via.placeholder.com/100', styles: { desktop: { default: { width: { value: 48, unit: 'px' }, height: { value: 48, unit: 'px' }, borderRadius: { value: 9999, unit: 'px' } } } } }),
                    createEl('flexbox', 'Author Details', { styles: { desktop: { default: { flexDirection: 'column' } } } }, [
                        createEl('heading', 'Name', { tag: 'h4', content: { innerText: 'Jane Smith' }, styles: { desktop: { default: { fontSize: { value: 16, unit: 'px' }, fontWeight: '700' } } } }),
                        createEl('paragraph', 'Role', { content: { innerText: 'CTO at TechCorp' }, styles: { desktop: { default: { color: '#6b7280', fontSize: { value: 14, unit: 'px' } } } } })
                    ])
                ])
            ]))
        ])
    ])
]);
saveStencil('testimonials', 'Modern Testimonials', 9, testimonials);

console.log("Stencils created!");
