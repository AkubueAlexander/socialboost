<script>
const ordersFromPHP = <?php echo json_encode($rowsOrder, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

function orderTable() {
    return {
        // table state
        search: '',
        currentPage: 1,
        perPage: 5,
        startIndex: 0,
        rows: [],
        filtered: [],

        // modal state
        modalOpen: false,
        isEditing: false,
        modalData: {},
        statuses: ['Pending', 'In Progress', 'Completed', 'Cancelled'],

        // helpers
        toISODate(dateStr) {
            // robust ISO yyyy-mm-dd for input[type=date]
            const d = dateStr ? new Date(dateStr) : new Date();
            // avoid timezone offset issues
            const tz = d.getTimezoneOffset() * 60000;
            return new Date(d.getTime() - tz).toISOString().slice(0, 10);
        },
        formatDisplayDate(iso) {
            if (!iso) return '';
            const [y, m, d] = iso.split('-');
            const dt = new Date(`${y}-${m}-${d}T00:00:00`);
            return dt.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        },

        init() {
            this.rows = ordersFromPHP.map(order => {
                const amountNum = parseFloat(order.amountSpent ?? order.amount ?? 0) || 0;
                const rawDate = order.orderDate ?? order.date ?? new Date().toISOString();
                const iso = this.toISODate(rawDate);
                return {
                    id: order.orderId,
                    fullName: order.fullName,
                    title: order.title,
                    amountValue: amountNum, // numeric, used in modal input
                    amount: `$${amountNum.toFixed(2)}`, // formatted, used in table
                    quantity: Number(order.quantity) || 0,
                    status: order.status ?? 'Pending',
                    socialurl: order.socialUrl ?? '',
                    dateISO: iso, // yyyy-mm-dd for input[type=date]
                    date: this.formatDisplayDate(iso) // pretty for table
                };
            });
            this.filtered = this.rows;
        },

        filterRows() {
            const keyword = this.search.toLowerCase();
            this.filtered = this.rows.filter(row =>
                Object.values(row).some(value => String(value).toLowerCase().includes(keyword))
            );
            this.currentPage = 1;
        },
        paginatedRows() {
            this.startIndex = (this.currentPage - 1) * this.perPage;
            return this.filtered.slice(this.startIndex, this.endIndex());
        },
        endIndex() {
            return this.startIndex + this.perPage;
        },
        totalPages() {
            return Math.ceil(this.filtered.length / this.perPage) || 1;
        },
        nextPage() {
            if (this.currentPage < this.totalPages()) this.currentPage++;
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },

        // modal actions
        showModal(row, editMode = false) {
            this.modalData = {
                ...row
            }; // clone row for editing
            this.isEditing = !!editMode;
            this.modalOpen = true;
        },
        saveEdit() {
            // apply modal changes back to table row
            const i = this.rows.findIndex(r => r.id === this.modalData.id);
            if (i !== -1) {
                const amountNum = Number(this.modalData.amountValue) || 0;
                const iso = this.modalData.dateISO || this.toISODate(new Date());
                this.rows[i] = {
                    ...this.rows[i],
                    fullName: this.modalData.fullName,
                    title: this.modalData.title,
                    amountValue: amountNum,
                    amount: `$${amountNum.toFixed(2)}`,
                    quantity: Number(this.modalData.quantity) || 0,
                    status: this.modalData.status,
                    socialurl: this.modalData.socialurl,
                    dateISO: iso,
                    date: this.formatDisplayDate(iso)
                };
                // refresh filtered so table reflects changes immediately
                this.filterRows();
            }
            this.modalOpen = false;

            // TODO: send AJAX to your PHP endpoint to persist changes server-side
            // fetch('update-order.php', { method:'POST', body: JSON.stringify(this.rows[i]) })
        },

        confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.rows = this.rows.filter(r => r.id !== id);
                    this.filterRows();
                    Swal.fire("Deleted!", "The order has been removed.", "success");
                    // TODO: also delete from server via AJAX
                }
            });
        }
    };
}
</script>