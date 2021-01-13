<?php
phpinfo();
?>
Private Sub PrintExcelLayoutRingkasLocal()
'On Error GoTo PrintExcelLayout1_Error
Dim oExcel As New Excel.Application
Dim oWB As New Excel.Workbook
Dim oWS As New Excel.Worksheet
Dim rs As New ADODB.Recordset, cn As New ADODB.Connection
Dim stFlName As String, lnRow As Long, lnCol As Long, stWhere As String, stProjectNo As String, lnRowBreakProject As Long
Dim stSuppCode As String, stPurchaseNo As String, lnNou As Long, lnRowBreakSupp As Long, lnRowBreakPurchase As Long, crJumlah As Currency
Dim stSuppName As String, lnCount As Long
Dim crSubTotal As Currency, crSubDisc As Currency, crSubPPN As Currency, crSubTotalAll As Currency, crSubRetur As Currency, crSubPayment As Currency, crSubSaldo As Currency
Dim crSubTotalProject As Currency, crSubDiscProject As Currency, crSubPPNProject As Currency, crSubTotalAllProject As Currency, crSubReturProject As Currency, crSubPaymentProject As Currency, crSubSaldoProject As Currency
Dim stSupTTL1 As String, stSupTTL2 As String, stSupTTL3 As String, stSupTTL4 As String, stSupTTL5 As String, stSupTTL6 As String, stSupTTL7 As String
Dim stTTL1 As String, stTTL2 As String, stTTL3 As String, stTTL4 As String, stTTL5 As String, stTTL6 As String, stTTL7 As String

Set cn = CurrentProject.Connection
stWhere = GetWhere()
stInfo = GetInfo()
stFlName = CurrentProject.Path & "\LapPurchaseRingkasLocal" & Format(Date, "ddmmyyyy") & ".xls"
If Dir(stFlName) <> "" Then
    Kill stFlName
End If
stSupTTL1 = ""
stSupTTL2 = ""
stSupTTL3 = ""
stSupTTL4 = ""
stSupTTL5 = ""
stSupTTL6 = ""
stSupTTL7 = ""

stTTL1 = ""
stTTL2 = ""
stTTL3 = ""
stTTL4 = ""
stTTL5 = ""
stTTL6 = ""
stTTL7 = ""
Set oWB = oExcel.Workbooks.Add
oWB.SaveAs (stFlName)
Set oWS = oWB.ActiveSheet

'create header
lnRow = 1
oWS.Cells(lnRow, 1) = "LAPORAN FAKTUR PEMBELIAN LOCAL PER-PROYEK PER-SUPPLIER (" & cstCompName & ") DAN PEMBAYARAN"
lnRow = lnRow + 1
oWS.Cells(lnRow, 1) = stInfo
lnRow = lnRow + 2
oWS.Cells(lnRow, 1) = "No."
oWS.Cells(lnRow, 2) = "No.Faktur"
oWS.Cells(lnRow, 3) = "Tanggal"
oWS.Cells(lnRow, 4) = "No.P/O"
oWS.Cells(lnRow, 5) = "M.Uang"
oWS.Cells(lnRow, 6) = "No.Subkon"
oWS.Cells(lnRow, 7) = "Memo"
oWS.Cells(lnRow, 8) = "SubTotal"
oWS.Cells(lnRow, 9) = "Discount"
oWS.Cells(lnRow, 10) = "PPN"
oWS.Cells(lnRow, 11) = "Total"
oWS.Cells(lnRow, 12) = "Retur"
oWS.Cells(lnRow, 13) = "Pembayaran"
oWS.Cells(lnRow, 14) = "Saldo"


Call Exl.SetExcelHAllignment(oExcel, 1, lnRow, 22, lnRow, 3)
Call Exl.SetExcelColumnWidth(oWS, 1, 1, 5)
Call Exl.SetExcelColumnWidth(oWS, 2, 2, 20)
Call Exl.SetExcelColumnWidth(oWS, 3, 3, 12)
Call Exl.SetExcelColumnWidth(oWS, 4, 4, 20)
Call Exl.SetExcelColumnWidth(oWS, 5, 5, 10)
Call Exl.SetExcelColumnWidth(oWS, 6, 6, 20)
Call Exl.SetExcelColumnWidth(oWS, 7, 7, 40)
Call Exl.SetExcelColumnWidth(oWS, 8, 8, 15)
Call Exl.SetExcelColumnWidth(oWS, 9, 9, 15)
Call Exl.SetExcelColumnWidth(oWS, 10, 10, 15)
Call Exl.SetExcelColumnWidth(oWS, 11, 11, 15)
Call Exl.SetExcelColumnWidth(oWS, 12, 12, 15)
Call Exl.SetExcelColumnWidth(oWS, 13, 13, 15)
Call Exl.SetExcelColumnWidth(oWS, 14, 14, 15)
For lnCount = 8 To 14
    Call Exl.SetExcelColumnNumberFormat(oExcel, lnCount)
Next
Call Exl.SetExcelColumnDateFormat(oExcel, 3)
Call Exl.SetExcelBackColour(oExcel, 1, lnRow, 14, lnRow, Exl.cinExcelLightGrey)
Call Exl.SetExcelCellsBold(oExcel, lnRow, 1, lnRow, 14)
With rs
    lnNou = 0
    stSQL = "SELECT * FROM vLocalPurchaseAmountOutstanding " & GetWhere & " ORDER BY fstProjectNo, fstSuppName"
    
    .Open stSQL, cn, adOpenForwardOnly, adLockReadOnly
    'stSQL = InputBox("SQL", "", stSQL)
    Do While Not .EOF
        stProjectNo = .Fields("fstProjectNo")

        SysCmd acSysCmdSetStatus, "Writing Proyek : " & stProjectNo & "..."
        lnRow = lnRow + 1
        oWS.Cells(lnRow, 1) = "Proyek =>" & stProjectNo
        'Call Exl.SetExcelMerged(oExcel, 1, lnRow, 4, lnRow)
        Call Exl.SetExcelBackColour(oExcel, 1, lnRow, 2, lnRow, Exl.cinExcelLightGrey)
        Call Exl.SetExcelCellsBold(oExcel, lnRow, 1, lnRow, 4)

        lnRowBreakProject = lnRow + 1

        crSubTotalProject = 0
        crSubDiscProject = 0


        Do While Not .EOF And stProjectNo = .Fields("fstProjectNo")
            stSuppCode = .Fields("fstSuppCode")
            stSuppName = .Fields("fstSuppName")
            lnRow = lnRow + 1
            oWS.Cells(lnRow, 1) = .Fields("fstSuppName")
            'Call Exl.SetExcelMerged(oExcel, 1, lnRow, 4, lnRow)
            'Call Exl.SetExcelBackColour(oExcel, 1, lnRow, 4, lnRow, Exl.cinExcelLightGrey)
            Call Exl.SetExcelCellsBold(oExcel, lnRow, 1, lnRow, 4)
            lnRowBreakSupp = lnRow + 1
            crSubTotal = 0
            crSubDisc = 0
            crSubPPN = 0
            crSubTotalAll = 0
            crSubRetur = 0
            crSubPayment = 0
            crSubSaldo = 0
            Do While Not .EOF And stProjectNo = .Fields("fstProjectNo") And stSuppCode = .Fields("fstSuppCode")
                lnNou = lnNou + 1
                oWS.Cells(lnRow + 1, 1) = lnNou
                lnRow = lnRow + 1
                'stPurchaseNo = .Fields("fstLocalPurchaseNo")
                'lnRowBreakPurchase = lnRow + 1
                'oWS.Cells(lnRow + 1, 3) = .Fields("fstLocalPurchaseNo")
                oWS.Cells(lnRow, 2) = .Fields("fstLocalPurchaseNo")
                oWS.Cells(lnRow, 3) = .Fields("fdtPurchaseDate")
                oWS.Cells(lnRow, 4) = .Fields("fstLocalPONo")
                oWS.Cells(lnRow, 5) = .Fields("fstCurrCode")
                oWS.Cells(lnRow, 6) = .Fields("fstSPKSubkonNo")
                oWS.Cells(lnRow, 7) = .Fields("fstMemo")
                oWS.Cells(lnRow, 8) = .Fields("fdcJumlah")
                oWS.Cells(lnRow, 9) = .Fields("fdcSubDiscAmount")
                oWS.Cells(lnRow, 10) = .Fields("fdcPPNAmount")
                oWS.Cells(lnRow, 11) = .Fields("fdcTotalAmount")
                oWS.Cells(lnRow, 12) = .Fields("fdcTotalNettoReturn")
                oWS.Cells(lnRow, 13) = .Fields("fdcPaymentAmount")
                oWS.Cells(lnRow, 14) = .Fields("fdcOutstanding")
                crSubTotal = crSubTotal + .Fields("fdcJumlah")
                crSubDisc = crSubDisc + .Fields("fdcSubDiscAmount")
                crSubPPN = crSubPPN + .Fields("fdcPPNAmount")
                crSubTotalAll = crSubTotalAll + .Fields("fdcTotalAmount")
                crSubRetur = crSubRetur + .Fields("fdcTotalNettoReturn")
                crSubPayment = crSubPayment + .Fields("fdcPaymentAmount")
                crSubSaldo = crSubSaldo + .Fields("fdcOutstanding")
                'lnRow = lnRow + 1
                .MoveNext
                If .EOF Then
                    Exit Do
                End If
            Loop
            lnNou = 0
            lnRow = lnRow + 1
            oWS.Cells(lnRow, 7) = "Subtotal "
            oWS.Cells(lnRow, 8) = crSubTotal
            oWS.Cells(lnRow, 9) = crSubDisc
            oWS.Cells(lnRow, 10) = crSubPPN
            oWS.Cells(lnRow, 11) = crSubTotalAll
            oWS.Cells(lnRow, 12) = crSubRetur
            oWS.Cells(lnRow, 13) = crSubPayment
            oWS.Cells(lnRow, 14) = crSubSaldo
            'oWS.Cells(lnRow, 10) = Exl.GetExcelSUM(lnRowBreakSupp, 10, lnRow - 1, 10)
            'oWS.Cells(lnRow, 11) = Exl.GetExcelSUM(lnRowBreakSupp, 11, lnRow - 1, 11)
            'oWS.Cells(lnRow, 12) = Exl.GetExcelSUM(lnRowBreakSupp, 12, lnRow - 1, 12)
            'oWS.Cells(lnRow, 13) = Exl.GetExcelSUM(lnRowBreakSupp, 13, lnRow - 1, 13)
            'oWS.Cells(lnRow, 14) = Exl.GetExcelSUM(lnRowBreakSupp, 14, lnRow - 1, 14)
            'oWS.Cells(lnRow, 15) = Exl.GetExcelSUM(lnRowBreakSupp, 15, lnRow - 1, 15)
            'oWS.Cells(lnRow, 16) = Exl.GetExcelSUM(lnRowBreakSupp, 16, lnRow - 1, 16)
            'stSupTTL1 = stSupTTL1 + Exl.getExcelColumnName(10) + Trim(Str(lnRow)) + "+"
            'stSupTTL2 = stSupTTL2 + Exl.getExcelColumnName(11) + Trim(Str(lnRow)) + "+"
            'stSupTTL3 = stSupTTL3 + Exl.getExcelColumnName(12) + Trim(Str(lnRow)) + "+"
            'stSupTTL4 = stSupTTL4 + Exl.getExcelColumnName(13) + Trim(Str(lnRow)) + "+"
            'stSupTTL5 = stSupTTL5 + Exl.getExcelColumnName(14) + Trim(Str(lnRow)) + "+"
            'stSupTTL6 = stSupTTL6 + Exl.getExcelColumnName(15) + Trim(Str(lnRow)) + "+"
            'stSupTTL7 = stSupTTL7 + Exl.getExcelColumnName(16) + Trim(Str(lnRow)) + "+"
            'Call Exl.SetExcelMerged(oExcel, 7, lnRow, 8, lnRow)
            'Call Exl.SetExcelBackColour(oExcel, 8, lnRow, 16, lnRow, Exl.cinExcelLightGrey)
            Call Exl.SetExcelCellsBold(oExcel, lnRow, 7, lnRow, 14)
            
            crSubTotalProject = crSubTotalProject + crSubTotal
            crSubDiscProject = crSubDiscProject + crSubDisc
            crSubPPNProject = crSubPPNProject + crSubPPN
            crSubTotalAllProject = crSubTotalAllProject + crSubTotalAll
            crSubReturProject = crSubReturProject + crSubRetur
            crSubPaymentProject = crSubPaymentProject + crSubPayment
            crSubSaldoProject = crSubSaldoProject + crSubSaldo
            
            '.MoveNext
            If .EOF Then
                Exit Do
            End If
    
            'lnRow = lnRow + 1
        Loop
        lnRow = lnRow + 1
        oWS.Cells(lnRow, 7) = "Total Proyek = " & stProjectNo
        oWS.Cells(lnRow, 8) = crSubTotalProject
        oWS.Cells(lnRow, 9) = crSubDiscProject
        oWS.Cells(lnRow, 10) = crSubPPNProject
        oWS.Cells(lnRow, 11) = crSubTotalAllProject
        oWS.Cells(lnRow, 12) = crSubReturProject
        oWS.Cells(lnRow, 13) = crSubPaymentProject
        oWS.Cells(lnRow, 14) = crSubSaldoProject
        'If stSupTTL1 <> "" Then
        '    oWS.Cells(lnRow, 10) = "=" + Left(stSupTTL1, Len(stSupTTL1) - 1)
        '    oWS.Cells(lnRow, 11) = "=" + Left(stSupTTL2, Len(stSupTTL2) - 1)
        '    oWS.Cells(lnRow, 12) = "=" + Left(stSupTTL3, Len(stSupTTL3) - 1)
        '    oWS.Cells(lnRow, 13) = "=" + Left(stSupTTL4, Len(stSupTTL4) - 1)
        '    oWS.Cells(lnRow, 14) = "=" + Left(stSupTTL5, Len(stSupTTL5) - 1)
        '    oWS.Cells(lnRow, 15) = "=" + Left(stSupTTL6, Len(stSupTTL6) - 1)
        '    oWS.Cells(lnRow, 16) = "=" + Left(stSupTTL7, Len(stSupTTL7) - 1)
        'End If
        'oWS.Cells(lnRow, 10) = Exl.GetExcelSUM(lnRowBreakProject, 10, lnRow - 1, 10)
        'oWS.Cells(lnRow, 11) = Exl.GetExcelSUM(lnRowBreakProject, 11, lnRow - 1, 11)
        'oWS.Cells(lnRow, 12) = Exl.GetExcelSUM(lnRowBreakProject, 12, lnRow - 1, 12)
        'oWS.Cells(lnRow, 13) = Exl.GetExcelSUM(lnRowBreakProject, 13, lnRow - 1, 13)
        'oWS.Cells(lnRow, 14) = Exl.GetExcelSUM(lnRowBreakProject, 14, lnRow - 1, 14)
        'oWS.Cells(lnRow, 15) = Exl.GetExcelSUM(lnRowBreakProject, 15, lnRow - 1, 15)
        'oWS.Cells(lnRow, 16) = Exl.GetExcelSUM(lnRowBreakProject, 16, lnRow - 1, 16)
        stTTL1 = stTTL1 + Exl.getExcelColumnName(8) + Trim(Str(lnRow)) + "+"
        stTTL2 = stTTL2 + Exl.getExcelColumnName(9) + Trim(Str(lnRow)) + "+"
        stTTL3 = stTTL3 + Exl.getExcelColumnName(10) + Trim(Str(lnRow)) + "+"
        stTTL4 = stTTL4 + Exl.getExcelColumnName(11) + Trim(Str(lnRow)) + "+"
        stTTL5 = stTTL5 + Exl.getExcelColumnName(12) + Trim(Str(lnRow)) + "+"
        stTTL6 = stTTL6 + Exl.getExcelColumnName(13) + Trim(Str(lnRow)) + "+"
        stTTL7 = stTTL7 + Exl.getExcelColumnName(14) + Trim(Str(lnRow)) + "+"
        
        'Call Exl.SetExcelMerged(oExcel, 7, lnRow, 8, lnRow)
        Call Exl.SetExcelBackColour(oExcel, 7, lnRow, 14, lnRow, Exl.cinExcelLightGrey)
        Call Exl.SetExcelCellsBold(oExcel, lnRow, 7, lnRow, 14)

        'lnRow = lnRow + 1
    Loop
    lnRow = lnRow + 1
    oWS.Cells(lnRow, 1) = "Total Keseluruhan"
    If stTTL1 <> "" Then
        oWS.Cells(lnRow, 8) = "=" + Left(stTTL1, Len(stTTL1) - 1)
        oWS.Cells(lnRow, 9) = "=" + Left(stTTL2, Len(stTTL2) - 1)
        oWS.Cells(lnRow, 10) = "=" + Left(stTTL3, Len(stTTL3) - 1)
        oWS.Cells(lnRow, 11) = "=" + Left(stTTL4, Len(stTTL4) - 1)
        oWS.Cells(lnRow, 12) = "=" + Left(stTTL5, Len(stTTL5) - 1)
        oWS.Cells(lnRow, 13) = "=" + Left(stTTL6, Len(stTTL6) - 1)
        oWS.Cells(lnRow, 14) = "=" + Left(stTTL7, Len(stTTL7) - 1)
    End If
    Call Exl.SetExcelMerged(oExcel, 1, lnRow, 7, lnRow)
    Call Exl.SetExcelBackColour(oExcel, 1, lnRow, 14, lnRow, Exl.cinExcelLightGrey)
    Call Exl.SetExcelCellsBold(oExcel, lnRow, 1, lnRow, 14)

    .Close
End With
Call Exl.SetExcelBorder(oExcel, 1, 4, 14, lnRow)

oExcel.Visible = True
oWB.Save
GoTo PrintExcelLayout1

PrintExcelLayout1_Error:
MsgBox Err.Description

PrintExcelLayout1:
SysCmd acSysCmdClearStatus
Set rs = Nothing
Set cn = Nothing
Set oExcel = Nothing
Set oWB = Nothing
Set oWS = Nothing
End Sub