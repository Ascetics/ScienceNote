
---

**求不定积分$\int \frac{x-2}{x^2+2x+3} \mathrm{d}x$。**

$\begin{aligned}
\int \frac{x-2}{x^2+2x+3} \mathrm{d}x 
&=\int \frac{(x+1)-3}{(x+1)^2+2} \mathrm{d}x \\
&=\int \frac{(x+1)}{(x+1)^2+2} \mathrm{d}x - 3\int \frac{1}{(x+1)^2+2} \mathrm{d}x \\
&=\frac{1}{2}\ln(2+(x+1)^2)-\frac{3}{\sqrt{2}}\arctan \frac{x+1}{\sqrt{2}} +C \\
&=\frac{1}{2}\ln(x^2+2x+3)-\frac{3}{\sqrt{2}}\arctan \frac{x+1}{\sqrt{2}} +C
\end{aligned}$

---

**求不定积分$\int \frac{\cos x}{2\sin x + 3\cos x} \mathrm{d}x$。**

待定系数法。思路是将分子分为两部分，一部分可以与分母相除消除，另一部分是分母的导数。

设分子 $\cos x = A(2\sin x + 3\cos x) + B(2\sin x + 3\cos x)'$  
整理得到 $\cos x = (2A-3B)\sin x  + (3A+2B)\cos x$   
解方程组  

$\begin{cases}
2A-3B &= 0 \\
3A+2B &= 1
\end{cases}$

得到  

$\begin{cases}
A &= \frac{2}{13} \\
B &= \frac{3}{13}
\end{cases}$

那么

$\begin{aligned}
\int \frac{\cos x}{2\sin x + 3\cos x} \mathrm{d}x
&= \frac{3}{13}\int 1 \mathrm{d}x + \frac{2}{13}\int \frac{1}{2\sin x + 3\cos x} \mathrm{d}(2\sin x + 3\cos x) \\
&= \frac{3}{13}x+\frac{2}{13} \ln |2\sin x + 3\cos x| + C
\end{aligned}$

---

