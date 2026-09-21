/* Minimal jsOrgChart-compatible renderer (offline, no CDN).
 * API: new OrgChart(container, {nodes:[{id,parent,type,name,title}], onSelect(node)}).
 * Renders nested UL/LI tree with connector styling hooks (.org-node).
 */
(function () {
    function el(tag, cls, text) {
        const e = document.createElement(tag);
        if (cls) e.className = cls;
        if (text !== undefined) e.textContent = text;
        return e;
    }
    function buildTree(nodes) {
        const byParent = {};
        nodes.forEach((n) => {
            const k = n.parent === null || n.parent === undefined ? '__root__' : String(n.parent);
            (byParent[k] = byParent[k] || []).push(n);
        });
        function branch(parentKey) {
            const ul = el('ul', 'org-list');
            (byParent[parentKey] || []).forEach((n) => {
                const li = el('li', 'org-item');
                const btn = el('button', 'org-node org-node-' + n.type);
                btn.type = 'button';
                btn.dataset.nodeId = n.id;
                const nm = el('span', 'org-node-name', n.name);
                const tt = el('span', 'org-node-title', n.title || '');
                btn.appendChild(nm);
                btn.appendChild(tt);
                btn.addEventListener('click', () => api.select(n.id));
                li.appendChild(btn);
                const kids = byParent[String(n.id)];
                if (kids && kids.length) li.appendChild(branch(String(n.id)));
                ul.appendChild(li);
            });
            return ul;
        }
        return branch('__root__');
    }
    let api = null;
    window.OrgChart = function (container, opts) {
        const root = typeof container === 'string' ? document.querySelector(container) : container;
        const nodes = (opts && opts.nodes) || [];
        const onSelect = opts && opts.onSelect;
        root.innerHTML = '';
        root.classList.add('org-chart-root');
        root.appendChild(buildTree(nodes));
        api = {
            select(id) {
                root.querySelectorAll('.org-node-selected').forEach((x) => x.classList.remove('org-node-selected'));
                const b = root.querySelector('[data-node-id="' + CSS.escape(id) + '"]');
                if (b) {
                    b.classList.add('org-node-selected');
                    b.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }
                const node = nodes.find((n) => String(n.id) === String(id));
                if (node && onSelect) onSelect(node);
            },
        };
        const sel = opts && opts.selectedId;
        if (sel) api.select(sel);
        return api;
    };
})();
