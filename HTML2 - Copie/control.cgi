' control.cgi
if QUERY_STRING$ = "output1=ON" then
    OUT(1) = 1
elseif QUERY_STRING$ = "output1=OFF" then
    OUT(1) = 0
end if