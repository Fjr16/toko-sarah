<div class="modal-header border-bottom d-block">
    <h5 class="modal-title" id="modalLongTitle">Detail Penjualan</h5>
    {{-- <p class="small my-0 py-0 text-uppercase">Order ID : <span class="fw-bold">4910487129047124</span></p> --}}
    {{-- <p class="small my-0 py-0 text-uppercase">Order ID : <span class="fw-bold">-</span></p> --}}
  </div>
  <div class="modal-body">
      <div class="table-responsive">
          <table class="table">
              <tbody>
                  <tr>
                      <td>No. Nota : <span>{{ $item->selling_id ?? '-' }}</span></td>
                      <td>Tanggal : <span>{{ $item->created_at->format('d/M/Y') }}</span></td>
                  </tr>
                  <tr>
                      <td>Order : <span>{{ $item->user->name ?? '' }}</span></td>
                      <td>Jam : <span>{{ $item->created_at->format('H:i') }}</span></td>
                  </tr>
                  <tr>
                      <td>Kasir : <span>{{ $item->user->name ?? '' }}</span></td>
                      <td>Nama Order : <span>Pelanggan</span></td>
                  </tr>
              </tbody>
          </table>
      </div>
      <div class="table-responsive">
          <table class="table table-bordered">
              <thead>
                  <tr>
                      <th>Item</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Total</th>
                  </tr>
              </thead>
              
              <tbody>
                  @foreach ($item->sellingDetails as $detail)
                      <tr>
                          <td>{{ $detail->product_name ?? '' }}</td>
                          <td>{{ $detail->product_jumlah ?? '' }}</td>
                          <td>{{ $detail->product_harga ? 'Rp. ' . number_format($detail->product_harga) : '' }}</td>
                          <td>{{ $detail->product_sub_total ? 'Rp. ' . number_format($detail->product_sub_total) : '' }}</td>
                      </tr>
                  @endforeach
                  <tr>
                      <th colspan="3">Subtotal</th>
                      <th class="subtotal">{{ $item->total_kotor ? 'Rp. ' . number_format($item->total_kotor) : '-' }}</th>
                  </tr>
                  <tr>
                      <th colspan="3">Items</th>
                      <th class="totalItems">{{ $item->items ?? '-' }}</th>
                  </tr>
                  <tr>
                      <th colspan="3">Total Items</th>
                      <th class="totalQty">{{ $item->total_item ?? '-' }}</th>
                  </tr>
                  <tr>
                      <th colspan="3">Diskon</th>
                      <th class="totalDiskon">{{ $item->total_diskon ? 'Rp. ' . number_format($item->total_diskon) : '' }}</th>
                  </tr>
                  <tr>
                      <th colspan="3" class="fw-bold">Total</th>
                      <th class="fw-bold totalAkhir">{{ $item->total_bersih ? 'Rp. ' . number_format($item->total_bersih) : '-'  }}</th>
                  </tr>
                  <tr>
                      <th colspan="3">
                          Tipe Bayar
                      </th>
                      <th id="tipeBayar">{{ $item->metode_bayar ?? '' }}</th>
                  </tr>
                  <tr>
                      <th colspan="3">
                          Jumlah bayar
                      </th>
                      <th id="jmlBayar">{{ $item->jumlah_bayar ? 'Rp. ' . number_format($item->jumlah_bayar) : '-' }}</th>
                  </tr>
                  <tr>
                      <th colspan="3" class="fw-bold text-uppercase fst-italic">Kembalian</th>
                      <th class="kembalian">{{ $item->kembalian ? 'Rp. ' . number_format($item->kembalian) : '-' }}</th>
                  </tr>
              </tbody>
          </table>
      </div>
  </div>
  <div class="modal-footer">
      <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Tutup</button>
  </div>