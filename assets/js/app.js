// Application WillPharma - Version Statique
class WillPharmaApp {
    constructor() {
        this.products = [...mockProducts];
        this.transactions = [...mockTransactions];
        this.inventory = [...mockInventoryItems];
        this.reports = [...mockReports];
        this.currentSort = { column: null, order: 'asc' };
        
        this.init();
    }

    init() {
        this.setupNavigation();
        this.setupModal();
        this.updateDashboard();
        this.updateInventory();
        this.updateReports();
        this.populateProductSelect();
    }

    // Navigation
    setupNavigation() {
        const navTabs = document.querySelectorAll('.nav-tab');
        const tabContents = document.querySelectorAll('.tab-content');

        navTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const targetTab = tab.dataset.tab;
                
                // Update active nav tab
                navTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Update active content
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(targetTab).classList.add('active');
            });
        });
    }

    // Modal Management
    setupModal() {
        const form = document.getElementById('transactionForm');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.addTransaction();
        });

        // Update form fields based on transaction type
        document.getElementById('transactionType').addEventListener('change', () => {
            this.updateTransactionForm();
        });
    }

    updateTransactionForm() {
        const type = document.getElementById('transactionType').value;
        const supplierGroup = document.getElementById('supplierGroup');
        const customerGroup = document.getElementById('customerGroup');
        
        if (type === 'purchase') {
            supplierGroup.style.display = 'block';
            customerGroup.style.display = 'none';
            document.getElementById('supplier').required = true;
            document.getElementById('customer').required = false;
        } else {
            supplierGroup.style.display = 'none';
            customerGroup.style.display = 'block';
            document.getElementById('supplier').required = false;
            document.getElementById('customer').required = true;
        }
        
        this.updatePriceField();
    }

    updatePriceField() {
        const productId = document.getElementById('productSelect').value;
        const type = document.getElementById('transactionType').value;
        const unitPriceField = document.getElementById('unitPrice');
        
        if (productId) {
            const product = this.products.find(p => p.id === productId);
            if (product) {
                unitPriceField.value = type === 'purchase' ? product.purchasePrice : product.salePrice;
                this.updateTotal();
            }
        }
    }

    updateTotal() {
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const unitPrice = parseFloat(document.getElementById('unitPrice').value) || 0;
        const total = quantity * unitPrice;
        
        const totalDisplay = document.getElementById('totalDisplay');
        const totalAmount = document.getElementById('totalAmount');
        
        if (quantity > 0 && unitPrice > 0) {
            totalDisplay.style.display = 'block';
            totalAmount.textContent = total.toFixed(2);
        } else {
            totalDisplay.style.display = 'none';
        }
    }

    populateProductSelect() {
        const select = document.getElementById('productSelect');
        select.innerHTML = '<option value="">Sélectionner un produit</option>';
        
        this.products.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = product.name;
            select.appendChild(option);
        });
    }

    // Transaction Management
    addTransaction() {
        const formData = new FormData(document.getElementById('transactionForm'));
        const productId = formData.get('productSelect');
        const product = this.products.find(p => p.id === productId);
        
        if (!product) return;

        const transaction = {
            id: Date.now().toString(),
            type: formData.get('transactionType'),
            productId: productId,
            productName: product.name,
            quantity: parseInt(formData.get('quantity')),
            unitPrice: parseFloat(formData.get('unitPrice')),
            totalAmount: parseInt(formData.get('quantity')) * parseFloat(formData.get('unitPrice')),
            date: new Date().toISOString().split('T')[0],
            reference: formData.get('reference')
        };

        if (transaction.type === 'purchase') {
            transaction.supplier = formData.get('supplier');
        } else {
            transaction.customer = formData.get('customer');
        }

        this.transactions.unshift(transaction);
        this.updateProductStock(productId, transaction.quantity, transaction.type);
        this.updateDashboard();
        this.updateInventory();
        this.closeTransactionModal();
        this.resetTransactionForm();
    }

    updateProductStock(productId, quantity, type) {
        const productIndex = this.products.findIndex(p => p.id === productId);
        if (productIndex !== -1) {
            if (type === 'purchase') {
                this.products[productIndex].stock += quantity;
            } else {
                this.products[productIndex].stock = Math.max(0, this.products[productIndex].stock - quantity);
            }
        }

        // Update inventory
        const inventoryIndex = this.inventory.findIndex(i => i.productId === productId);
        if (inventoryIndex !== -1) {
            const item = this.inventory[inventoryIndex];
            if (type === 'purchase') {
                item.currentStock += quantity;
            } else {
                item.currentStock = Math.max(0, item.currentStock - quantity);
            }
            item.difference = item.currentStock - item.theoreticalStock;
            item.totalValue = item.currentStock * item.unitPrice;
            item.lastUpdated = new Date().toISOString().split('T')[0];
        }
    }

    resetTransactionForm() {
        document.getElementById('transactionForm').reset();
        document.getElementById('totalDisplay').style.display = 'none';
        this.updateTransactionForm();
    }

    // Dashboard Updates
    updateDashboard() {
        this.updateMetrics();
        this.updateTransactionsList();
        this.updateQuickAnalysis();
    }

    updateMetrics() {
        const totalPurchases = this.transactions
            .filter(t => t.type === 'purchase')
            .reduce((sum, t) => sum + t.totalAmount, 0);

        const totalSales = this.transactions
            .filter(t => t.type === 'sale')
            .reduce((sum, t) => sum + t.totalAmount, 0);

        const totalProfit = totalSales - totalPurchases;
        const profitMargin = totalSales > 0 ? (totalProfit / totalSales) * 100 : 0;

        const positiveSales = this.transactions
            .filter(t => t.type === 'sale')
            .reduce((sum, t) => sum + t.totalAmount, 0);

        document.getElementById('totalPurchases').textContent = `${totalPurchases.toFixed(2)} €`;
        document.getElementById('totalSales').textContent = `${totalSales.toFixed(2)} €`;
        document.getElementById('totalProfit').textContent = `${totalProfit.toFixed(2)} €`;
        document.getElementById('profitMargin').textContent = `Marge: ${profitMargin.toFixed(1)}%`;
        document.getElementById('positiveSales').textContent = `${positiveSales.toFixed(2)} €`;
    }

    updateTransactionsList() {
        const container = document.getElementById('transactionsList');
        const sortedTransactions = [...this.transactions].sort((a, b) => 
            new Date(b.date).getTime() - new Date(a.date).getTime()
        );

        if (sortedTransactions.length === 0) {
            container.innerHTML = '<div class="empty-state"><i class="fas fa-receipt"></i><p>Aucune transaction enregistrée</p></div>';
            return;
        }

        container.innerHTML = sortedTransactions.slice(0, 10).map(transaction => `
            <div class="transaction-item">
                <div class="transaction-left">
                    <div class="transaction-icon ${transaction.type}">
                        <i class="fas fa-${transaction.type === 'sale' ? 'arrow-up' : 'arrow-down'}"></i>
                    </div>
                    <div class="transaction-details">
                        <h4>${transaction.productName}</h4>
                        <div class="transaction-meta">
                            <span><i class="fas fa-calendar"></i> ${new Date(transaction.date).toLocaleDateString('fr-FR')}</span>
                            <span><i class="fas fa-hashtag"></i> ${transaction.reference}</span>
                        </div>
                    </div>
                </div>
                <div class="transaction-right">
                    <div class="transaction-amount ${transaction.type === 'sale' ? 'positive' : 'negative'}">
                        ${transaction.type === 'sale' ? '+' : '-'}${transaction.totalAmount.toFixed(2)} €
                    </div>
                    <div class="transaction-quantity">
                        ${transaction.quantity} × ${transaction.unitPrice.toFixed(2)} €
                    </div>
                </div>
                ${transaction.supplier ? `<div class="transaction-partner">Fournisseur: ${transaction.supplier}</div>` : ''}
                ${transaction.customer ? `<div class="transaction-partner">Client: ${transaction.customer}</div>` : ''}
            </div>
        `).join('');
    }

    updateQuickAnalysis() {
        // Top products
        const productSales = {};
        this.transactions
            .filter(t => t.type === 'sale')
            .forEach(t => {
                productSales[t.productId] = (productSales[t.productId] || 0) + t.quantity;
            });

        const topProducts = Object.entries(productSales)
            .sort(([,a], [,b]) => b - a)
            .slice(0, 3)
            .map(([productId, quantity]) => {
                const product = this.products.find(p => p.id === productId);
                return { product, quantity };
            });

        document.getElementById('topProducts').innerHTML = topProducts.map(({ product, quantity }) => `
            <div class="analysis-item">
                <span>${product.name}</span>
                <span class="analysis-value">${quantity} unités</span>
            </div>
        `).join('');

        // Low stock products
        const lowStockProducts = this.products
            .filter(p => p.stock <= p.minStock)
            .slice(0, 3);

        document.getElementById('lowStockProducts').innerHTML = lowStockProducts.map(product => `
            <div class="analysis-item">
                <span>${product.name}</span>
                <span class="analysis-value">${product.stock} unités</span>
            </div>
        `).join('');
    }

    // Inventory Updates
    updateInventory() {
        this.updateInventoryStats();
        this.updateInventoryTable();
        document.getElementById('lastUpdated').textContent = new Date().toLocaleDateString('fr-FR');
    }

    updateInventoryStats() {
        const totalValue = this.inventory.reduce((sum, item) => sum + item.totalValue, 0);
        const totalProducts = this.products.length;
        const lowStockCount = this.products.filter(p => p.stock <= p.minStock).length;
        const criticalStockCount = this.products.filter(p => p.stock <= p.minStock * 0.5).length;

        document.getElementById('totalStockValue').textContent = `${totalValue.toFixed(2)} €`;
        document.getElementById('totalProducts').textContent = totalProducts.toString();
        document.getElementById('lowStockCount').textContent = lowStockCount.toString();
        document.getElementById('criticalStockCount').textContent = criticalStockCount.toString();
    }

    updateInventoryTable() {
        const tbody = document.getElementById('inventoryTableBody');
        
        tbody.innerHTML = this.inventory.map(item => {
            const product = this.products.find(p => p.id === item.productId);
            if (!product) return '';

            const stockStatus = this.getStockStatus(item.currentStock, product.minStock);
            
            return `
                <tr>
                    <td>
                        <div class="product-info">
                            <div class="product-icon">
                                <i class="fas fa-pills"></i>
                            </div>
                            <div>
                                <div class="product-name">${item.productName}</div>
                                <div class="product-details">${product.category} • ${product.batchNumber}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="stock-actions">
                            <span class="font-medium">${item.currentStock}</span>
                            <button class="action-btn" onclick="app.editStock('${item.productId}', ${item.currentStock})">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                    <td>${item.theoreticalStock}</td>
                    <td>
                        <span class="difference-badge ${item.difference > 0 ? 'positive' : item.difference < 0 ? 'negative' : 'neutral'}">
                            ${item.difference > 0 ? '+' : ''}${item.difference}
                        </span>
                    </td>
                    <td class="font-medium">${item.totalValue.toFixed(2)} €</td>
                    <td>
                        <div class="status-badge ${stockStatus.class}">
                            <i class="fas fa-${stockStatus.icon}"></i>
                            ${stockStatus.text}
                        </div>
                    </td>
                    <td class="text-sm text-gray-500">Min: ${product.minStock}</td>
                </tr>
            `;
        }).join('');
    }

    getStockStatus(current, min) {
        if (current <= min * 0.5) {
            return { class: 'critical', icon: 'exclamation-triangle', text: 'Critique' };
        }
        if (current <= min) {
            return { class: 'low', icon: 'exclamation-triangle', text: 'Faible' };
        }
        return { class: 'good', icon: 'check-circle', text: 'Correct' };
    }

    editStock(productId, currentStock) {
        const newStock = prompt(`Nouveau stock pour ce produit:`, currentStock);
        if (newStock !== null && !isNaN(newStock) && newStock >= 0) {
            this.updateStock(productId, parseInt(newStock));
        }
    }

    updateStock(productId, newStock) {
        // Update product
        const productIndex = this.products.findIndex(p => p.id === productId);
        if (productIndex !== -1) {
            this.products[productIndex].stock = newStock;
        }

        // Update inventory
        const inventoryIndex = this.inventory.findIndex(i => i.productId === productId);
        if (inventoryIndex !== -1) {
            const item = this.inventory[inventoryIndex];
            item.currentStock = newStock;
            item.difference = newStock - item.theoreticalStock;
            item.totalValue = newStock * item.unitPrice;
            item.lastUpdated = new Date().toISOString().split('T')[0];
        }

        this.updateInventory();
        this.updateDashboard();
    }

    // Reports Updates
    updateReports() {
        this.updateReportsStats();
        this.updateReportsTable();
    }

    updateReportsStats() {
        const totalRevenue = this.reports.reduce((sum, report) => sum + report.totalSales, 0);
        const totalProfit = this.reports.reduce((sum, report) => sum + report.totalProfit, 0);
        const avgMargin = this.reports.length > 0 
            ? this.reports.reduce((sum, report) => sum + report.profitMargin, 0) / this.reports.length 
            : 0;
        const lowPerformingCount = this.reports.filter(report => 
            report.profitMargin < 15 || report.turnoverRate < 0.5
        ).length;

        document.getElementById('totalRevenue').textContent = `${totalRevenue.toFixed(2)} €`;
        document.getElementById('totalReportProfit').textContent = `${totalProfit.toFixed(2)} €`;
        document.getElementById('avgMargin').textContent = `${avgMargin.toFixed(1)}%`;
        document.getElementById('lowPerformingCount').textContent = lowPerformingCount.toString();
        document.getElementById('totalReportProducts').textContent = `sur ${this.reports.length} produits`;
    }

    updateReportsTable() {
        const tbody = document.getElementById('reportsTableBody');
        
        tbody.innerHTML = this.reports.map(report => `
            <tr>
                <td>
                    <div class="product-info">
                        <div class="product-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div>
                            <div class="product-name">${report.productName}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="category-badge">${report.category}</span>
                </td>
                <td>${report.totalPurchases.toFixed(2)} €</td>
                <td>${report.totalSales.toFixed(2)} €</td>
                <td class="font-medium ${report.totalProfit >= 0 ? 'text-green-600' : 'text-red-600'}">
                    ${report.totalProfit.toFixed(2)} €
                </td>
                <td>
                    <div class="profit-margin">
                        <div class="margin-value ${this.getMarginClass(report.profitMargin)}">
                            ${report.profitMargin.toFixed(1)}%
                        </div>
                        <div class="margin-bar">
                            <div class="margin-fill ${this.getMarginClass(report.profitMargin)}" 
                                 style="width: ${Math.min(report.profitMargin, 100)}%"></div>
                        </div>
                    </div>
                </td>
                <td>${report.stockValue.toFixed(2)} €</td>
                <td>
                    <span class="turnover-badge ${this.getTurnoverClass(report.turnoverRate)}">
                        ${report.turnoverRate.toFixed(1)}x
                    </span>
                </td>
            </tr>
        `).join('');
    }

    getMarginClass(margin) {
        if (margin >= 30) return 'high';
        if (margin >= 15) return 'medium';
        return 'low';
    }

    getTurnoverClass(rate) {
        if (rate >= 1) return 'high';
        if (rate >= 0.5) return 'medium';
        return 'low';
    }

    // Sorting functionality
    sortReports(column) {
        if (this.currentSort.column === column) {
            this.currentSort.order = this.currentSort.order === 'asc' ? 'desc' : 'asc';
        } else {
            this.currentSort.column = column;
            this.currentSort.order = 'desc';
        }

        this.reports.sort((a, b) => {
            const aValue = a[column];
            const bValue = b[column];
            
            if (typeof aValue === 'number' && typeof bValue === 'number') {
                return this.currentSort.order === 'asc' ? aValue - bValue : bValue - aValue;
            }
            
            return this.currentSort.order === 'asc' 
                ? String(aValue).localeCompare(String(bValue))
                : String(bValue).localeCompare(String(aValue));
        });

        this.updateReportsTable();
    }
}

// Modal functions
function openTransactionModal() {
    document.getElementById('transactionModal').classList.add('active');
    app.updateTransactionForm();
}

function closeTransactionModal() {
    document.getElementById('transactionModal').classList.remove('active');
    app.resetTransactionForm();
}

function updatePriceField() {
    app.updatePriceField();
}

function updateTotal() {
    app.updateTotal();
}

function sortReports(column) {
    app.sortReports(column);
}

// Initialize app when DOM is loaded
let app;
document.addEventListener('DOMContentLoaded', () => {
    app = new WillPharmaApp();
});