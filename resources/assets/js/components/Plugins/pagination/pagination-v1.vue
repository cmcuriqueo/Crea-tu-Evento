<template>
    <div v-if="totalItems != 'undefined' && totalItems > 0 && totalPages > 1" class="vuetable-pagination pull-right" style="
    padding-right: 12px;">
        <ul class="pagination">
            <li>
                <a href="#" @click.prevent="pageChanged(1)" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li v-for="n in paginationRange" :class="activePage(n)">
                <a href="#" @click.prevent="pageChanged(n)">{{ n }}</a>
            </li>
            <li>
                <a href="#" @click.prevent="pageChanged(lastPage)" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            // Current Page
            currentPage: {
                type: Number,
                required: true
            },
            // Total page
            totalPages: {
                type: Number
            },
            // Items per page
            itemsPerPage: {
                type: Number
            },
            // Total items
            totalItems: {
                type: Number
            },
            // Visible Pages
            pagesVisible: {
                type: Number,
                default: 5
            }
        },
        computed: {
            visiblePages: {
                get: function() {
                    return  parseInt(this.pagesVisible);
                }
            },
            lastPage: { 
                get: function () {
                  if (this.totalPages) {
                    return this.totalPages
                  } else {
                    return this.totalItems % this.itemsPerPage === 0
                      ? this.totalItems / this.itemsPerPage
                      : Math.floor(this.totalItems / this.itemsPerPage) + 1
                  }
                }
            },
            paginationRange: {
                get: function () {
                  let start = this.currentPage - this.visiblePages / 2 <= 0
                                ? 1 : this.currentPage + this.visiblePages / 2 > this.lastPage
                                ? this.lowerBound(this.lastPage - this.visiblePages + 1, 1)
                                : Math.ceil(this.currentPage - this.visiblePages / 2)
                  let range = []
                  for (let i = 0; i < this.visiblePages && i < this.lastPage; i++) {
                    range.push(start + i)
                  }
                  return range
                }
            }
        },
        methods: {
            pageChanged (pageNum) {
              this.$emit('page-changed', pageNum)
            },
            activePage (pageNum) {
                return this.currentPage === pageNum ? 'active' : ''
            },
            lowerBound (num, limit) {
                return num >= limit ? num : limit
            }
        }
    }
</script>