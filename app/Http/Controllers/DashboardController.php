<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserRepositoryInterface;

class DashboardController extends Controller
{
  private UserRepositoryInterface $userRepo;
  private VendorRepositoryInterface $vendorRepo;
  private InvoiceRepositoryInterface $invoiceRepo;

  public function __construct(
    UserRepositoryInterface $userRepo,
    VendorRepositoryInterface $vendorRepo,
    InvoiceRepositoryInterface $invoiceRepo
  ) {
    $this->userRepo = $userRepo;
    $this->vendorRepo = $vendorRepo;
    $this->invoiceRepo = $invoiceRepo;
  }
  public function stats(Request $request)
  {
    $tenantId = $request->attributes->get('tenant_id');

    $totalUsers = $this->userRepo->getTenantUser($tenantId);
    $totalCount = $totalUsers->count();
    $adminCount = $totalUsers->where('role', 'admin')->count();
    $accountantCount = $totalUsers->where('role', 'accountant')->count();

    $vendors = $this->vendorRepo->getAll($tenantId);
    $totalVendors = $vendors->count();

    $invoices = $this->invoiceRepo->getAll($tenantId);
    $totalInvoices = $invoices->count();
    $totalInvoicesDraft = $invoices->where('status', 'draft')->count();
    $totalInvoicesSent = $invoices->where('status', 'sent')->count();
    $totalInvoicesPaid = $invoices->where('status', 'paid')->count();
    $totalInvoicesOverdue = $invoices->where('status', 'overdue')->count();

    return response()->json([
      'total_users' => $totalCount,
      'admin_users' => $adminCount,
      'accountant_users' => $accountantCount,
      'total_vendors' => $totalVendors,
      'total_invoices' => $totalInvoices,
      'invoices_draft' => $totalInvoicesDraft,
      'invoices_sent' => $totalInvoicesSent,
      'invoices_paid' => $totalInvoicesPaid,
      'invoices_overdue' => $totalInvoicesOverdue,
    ]);
  }
}
