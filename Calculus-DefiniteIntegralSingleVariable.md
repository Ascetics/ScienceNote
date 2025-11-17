
|![封面：牛顿-莱布尼茨公式](./images/Calculus/牛顿-莱布尼茨公式.jpg)|
|:--:|
| 封面：牛顿-莱布尼茨公式 |

<div style="page-break-after: always;"></div>

# 求一元定积分

---

**求积分$I=\int_{-1}^1 \frac{x^2}{1+e^x} \mathrm{d}x$。**

$\begin{aligned}
I
&=\int_{-1}^1 \frac{x^2}{1+e^x} \mathrm{d}x =\int_0^1 \frac{x^2}{1+e^x} \mathrm{d}x + \int_{-1}^0 \frac{x^2}{1+e^x} \mathrm{d}x \\
&\xlongequal{\quad 第二部分替换t=-x \quad}\int_0^1 \frac{x^2}{1+e^x} \mathrm{d}x + \int_{1}^0 \frac{(-x)^2}{1+e^{-x}} \mathrm{d}(-x) \\
&=\int_0^1 \frac{x^2}{1+e^x} \mathrm{d}x + \int_0^1 \frac{x^2}{1+e^{-x}} \mathrm{d}x =\int_0^1 \frac{x^2}{1+e^x} + \frac{x^2}{1+e^{-x}} \mathrm{d}x \\
&=\int_0^1 \frac{x^2(2+e^{-x}+e^x)}{2+e^{-x}+e^x} \mathrm{d}x =\int_0^1 x^2 \mathrm{d}x \\
&= \left.\frac{1}{3}x^3 \right|_0^1 = \frac{1}{3}
\end{aligned}$

---

**求积分$I=\int_0^{\frac{\pi}{2}} \frac{\sin 2x}{1+\sin^4 x} \mathrm{d}x$。**

$\begin{aligned}
I
&=\int_0^{\frac{\pi}{2}} \frac{\sin 2x}{1+\sin^4 x} \mathrm{d}x \\
&\xlongequal{\quad \sin 2x = 2\sin x \cos x \quad} \int_0^{\frac{\pi}{2}} \frac{2\sin x \cos x}{1+\sin^4 x} \mathrm{d}x \\
&\xlongequal{\quad t=\sin x \quad} \int_0^1 \frac{2t}{1+t^4} \mathrm{d}t =\int_0^1 \frac{1}{1+t^4} \mathrm{d}t^2 \\
&\xlongequal{\quad u=t^2 \quad} \int_0^1 \frac{1}{1+u^2} \mathrm{d}u =\left.\arctan u \right|_0^1 =\frac{\pi}{4}
\end{aligned}$

---


**求 $\lim_{n\to \infty} \int_0^1 \frac{x^n}{1+x} \mathrm{d}x$。**

$\begin{aligned}
\int_0^1 \frac{x^n}{1+x} \mathrm{d}x
&= \int_0^1 \frac{(1+x)((-1)^{0}x^{n-1} + (-1)^{1}x^{n-2} + \dots + (-1)^{n-2}x^{1} + (-1)^{n-1}x^{0}) + (-1)^n}{1+x} \mathrm{d}x \\
&= \int_0^1 ((-1)^0x^{n-1} + (-1)^1x^{n-2} + \dots + (-1)^{n-2}x^1 + (-1)^{n-1}x^0) + \frac{(-1)^n}{1+x} \mathrm{d}x \\
&= \left. \frac{(-1)^{0}x^{n}}{n} + \frac{(-1)^{1}x^{n-1}}{n-1} + \dots + \frac{(-1)^{n-2}x^{2}}{2} + \frac{(-1)^{n-1}x}{1} + (-1)^n\ln(1+x) \right|_0^1 \\
&= \left.\sum_{k=1}^{n}\frac{(-1)^{n-k}x^{k}}{k} + (-1)^n\ln(1+x) \right|_0^1 \\
&= (-1)^n(\sum_{k=1}^{n}\frac{(-1)^{k}}{k} + \ln 2) \\
\end{aligned}$

利用结论，调和级数奇数项和与偶数项和的差为
$1 - \frac{1}{2} + \frac{1}{3} - \frac{1}{4} + \frac{1}{5} - \frac{1}{6} + \dots  = \ln 2$

$\begin{aligned}
\lim_{n\to \infty} \int_0^1 \frac{x^n}{1+x} \mathrm{d}x
&= (-1)^{+\infty}(\sum_{k=1}^{{+\infty}}\frac{(-1)^{k}}{k} + \ln 2) = 0
\end{aligned}$

---

**求 $\int_0^\pi \frac{x\sin x}{1+\cos^2 x} \mathrm{d}x$。**

$\begin{aligned}
\int_0^\pi \frac{x\sin x}{1+\cos^2 x} \mathrm{d}x
&\xlongequal {\quad x=\frac{\pi}{2}-t \quad} \pi\int_{0}^{\frac{\pi}{2}} \frac{\cos t}{1+\sin^2 t} \mathrm{d}t \\
&\xlongequal {\quad u=\sin t \quad} \pi\int_{0}^{1} \frac{1}{1+u^2} \mathrm{d}u \\
&= \left. \pi\arctan u \right|_0^1 = \frac{\pi^2}{4}
\end{aligned}$

---
